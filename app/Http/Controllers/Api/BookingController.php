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
            'seat_ids.*' => 'exists:seats,id'
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        $validSeats = \App\Models\Seat::whereIn('id', $request->seat_ids)
            ->where('schedule_id', $request->schedule_id)
            ->count();

        if ($validSeats !== count($request->seat_ids)) {
            return response()->json([
                'message' => 'Invalid seat selection'
            ], 400);
        }
        if (count($request->seat_ids) !== count(array_unique($request->seat_ids))) {
            return response()->json([
                'message' => 'Duplicate seat selection'
            ], 400);
        }
        return DB::transaction(function () use ($request, $schedule) {

            DB::table('seats')
                ->whereIn('id', $request->seat_ids)
                ->lockForUpdate()
                ->get();

            $bookedSeats = DB::table('booking_seats')
                ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->where('bookings.schedule_id', $request->schedule_id)
                ->whereIn('booking_seats.seat_id', $request->seat_ids)
                ->where(function ($q) {
                    $q->where('bookings.payment_status', 'paid')
                        ->orWhere(function ($q2) {
                            $q2->where('bookings.payment_status', 'pending')
                                ->where('bookings.expired_at', '>', now());
                        });
                })
                ->pluck('seat_id')
                ->toArray();
            if (count($bookedSeats) > 0) {
                return response()->json([
                    'message' => 'Seat already booked',
                    'conflict_seats' => $bookedSeats
                ], 409);
            }
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

            foreach ($request->seat_ids as $seatId) {
                DB::table('booking_seats')->insert([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId
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
}
