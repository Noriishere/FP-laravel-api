<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function myBookings()
    {
        $user = auth('api')->user();

        $bookings = $user->bookings()
            ->with(['schedule.route', 'bookingSeats.seat'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function mySchedules()
    {
        $user = auth('api')->user();

        if ($user->role !== 'driver') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $schedules = \App\Models\Schedule::where('driver_id', $user->driver->id)
            ->with(['route', 'vehicle', 'bookings'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }
}
