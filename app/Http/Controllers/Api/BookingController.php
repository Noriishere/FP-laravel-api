<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer'
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // 🔥 Ambil seat berdasarkan seat_number
        $seats = \App\Models\Seat::where('schedule_id', $request->schedule_id)
            ->whereIn('seat_number', $request->seat_ids)
            ->get();

        // ❗ Validasi: semua seat harus valid
        if ($seats->count() !== count($request->seat_ids)) {
            return response()->json([
                'message' => 'Invalid seat selection'
            ], 400);
        }

        // ❗ Duplicate request
        if (count($request->seat_ids) !== count(array_unique($request->seat_ids))) {
            return response()->json([
                'message' => 'Duplicate seat selection'
            ], 400);
        }

        // 🔥 Mapping seat_number → seat_id
        $seatMap = $seats->pluck('id', 'seat_number');
        $seatIds = array_values($seatMap->toArray());

        return DB::transaction(function () use ($request, $schedule, $seatIds) {

            // 🔒 Lock seat biar aman race condition
            DB::table('seats')
                ->whereIn('id', $seatIds)
                ->lockForUpdate()
                ->get();

            // 🔥 Cek sudah dibooking
            $bookedSeats = DB::table('booking_seats')
                ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->where('bookings.schedule_id', $request->schedule_id)
                ->whereIn('booking_seats.seat_id', $seatIds)
                ->where(function ($q) {
                    $q->where('bookings.payment_status', 'paid')
                        ->orWhere(function ($q2) {
                            $q2->where('bookings.payment_status', 'pending')
                                ->where('bookings.expired_at', '>', now());
                        });
                })
                ->pluck('booking_seats.seat_id')
                ->toArray();

            if (count($bookedSeats) > 0) {
                return response()->json([
                    'message' => 'Seat already booked',
                    'conflict_seats' => $bookedSeats
                ], 409);
            }

            // ❗ Cegah double pending booking
            $existing = Booking::where('user_id', Auth::id())
                ->where('schedule_id', $request->schedule_id)
                ->where('payment_status', 'pending')
                ->where('expired_at', '>', now())
                ->exists();

            if ($existing) {
                return response()->json([
                    'message' => 'You still have unpaid booking for this schedule'
                ], 400);
            }

            $orderId = 'INV-' . now()->format('YmdHis') . '-' . uniqid();

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'schedule_id' => $request->schedule_id,
                'order_id' => $orderId,
                'total_seat' => count($request->seat_ids),
                'total_price' => count($request->seat_ids) * $schedule->price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_provider' => null,
                'expired_at' => now()->addMinutes(15)
            ]);

            foreach ($seatIds as $seatId) {
                DB::table('booking_seats')->insert([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'schedule_id' => $booking->schedule_id
                ]);
            }

            return response()->json([
                'message' => 'Booking created, waiting payment',
                'booking_id' => $booking->id,
                'order_id' => $booking->order_id,
                'total_price' => $booking->total_price
            ]);
        });
    }
    public function scan(Request $request)
    {
        $user = auth('api')->user();
        $request->validate([
            'order_id' => 'required|string'
        ]);

        $booking = Booking::with(['schedule.route', 'user'])
            ->where('order_id', $request->order_id)
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->payment_status === 'pending' && $booking->expired_at < now()) {
            return response()->json([
                'message' => 'Booking expired'
            ], 400);
        }

        if ($booking->payment_status !== 'paid') {
            return response()->json([
                'message' => 'Payment not completed'
            ], 400);
        }

        if ($booking->checked_at) {
            return response()->json([
                'message' => 'Ticket already used'
            ], 400);
        }

        $booking->update([
            'checked_at' => now(),
            'checked_by' => $user->id
        ]);

        return response()->json([
            'message' => 'Valid ticket',
            'data' => [
                'user' => $booking->user->name,
                'route' => $booking->schedule->route->start . ' - ' . $booking->schedule->route->end,
                'total_seat' => $booking->total_seat
            ]
        ]);
    }
}
