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
            ->where('vehicle_id', $schedule->vehicle_id)
            ->count();

        if ($validSeats !== count($request->seat_ids)) {
            return response()->json([
                'message' => 'Invalid seat selection'
            ], 400);
        }
        return DB::transaction(function () use ($request, $schedule) {

            $bookedSeats = DB::table('booking_seats')
                ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->where('bookings.schedule_id', $request->schedule_id)
                ->whereIn('booking_seats.seat_id', $request->seat_ids)
                ->lockForUpdate()
                ->pluck('seat_id')
                ->toArray();

            if (count($bookedSeats) > 0) {
                return response()->json([
                    'message' => 'Seat already booked',
                    'conflict_seats' => $bookedSeats
                ], 409);
            }

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'schedule_id' => $request->schedule_id,
                'total_price' => count($request->seat_ids) * $schedule->price,
                'status' => 'confirmed'
            ]);

            foreach ($request->seat_ids as $seatId) {
                DB::table('booking_seats')->insert([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId
                ]);
            }

            return response()->json([
                'message' => 'Booking success',
                'booking_id' => $booking->id,
                'seats' => $request->seat_ids
            ]);
        });
    }
}
