<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        $schedule = \App\Models\Schedule::where('id', $request->schedule_id)
            ->where('driver_id', auth('api')->id())
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized schedule'
            ], 403);
        }

        $last = Location::where('schedule_id', $request->schedule_id)
            ->latest('recorded_at')
            ->first();

        // 🔥 prevent spam (min 5 detik)
        if ($last && now()->diffInSeconds($last->recorded_at) < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Too fast update'
            ], 429);
        }

        $location = Location::create([
            'schedule_id' => $request->schedule_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'heading' => $request->heading,
            'recorded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    public function tracking($scheduleId)
    {
        $hasBooking = \App\Models\Booking::where('user_id', auth('api')->id())
            ->where('schedule_id', $scheduleId)
            ->exists();

        if (!$hasBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        $location = Location::where('schedule_id', $scheduleId)
            ->latest('recorded_at')
            ->first();
        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No tracking data yet'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'recorded_at' => $location->recorded_at,
            ]
        ]);
    }
}
