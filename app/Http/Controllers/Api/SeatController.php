<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    public function availability($id)
    {
        $schedule = Schedule::with(['vehicle.seats'])->findOrFail($id);

        $seats = $schedule->vehicle->seats;

        $bookedSeatIds = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.schedule_id', $id)
            ->pluck('seat_id')
            ->toArray();

        $result = $seats->map(function ($seat) use ($bookedSeatIds) {
            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'status' => in_array($seat->id, $bookedSeatIds) ? 'booked' : 'available'
            ];
        });

        return response()->json($result);
    }
}
