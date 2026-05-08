<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Schedule;
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
            'accuracy' => 'nullable|numeric',
            'is_mocked' => 'nullable|boolean',
        ]);

        $driver = auth('api')->user()?->driver;

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 403);
        }

        $schedule = Schedule::with([
            'route.stops',
            'stopTimes.stop'
        ])
            ->where('id', $request->schedule_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized schedule'
            ], 403);
        }

        if ($schedule->status === 'scheduled') {
            $schedule->update([
                'status' => 'on-going'
            ]);
        }

        if ($schedule->status !== 'on-going') {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not active'
            ], 400);
        }

        $last = Location::where('schedule_id', $request->schedule_id)
            ->latest('recorded_at')
            ->first();

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
            'accuracy' => $request->accuracy,
            'is_mocked' => $request->is_mocked ?? false,
            'recorded_at' => now(),
        ]);

        foreach ($schedule->stopTimes as $stopTime) {

            if ($stopTime->status === 'arrived') {
                continue;
            }

            $distance = $this->haversine(
                $request->latitude,
                $request->longitude,
                $stopTime->stop->lat,
                $stopTime->stop->lng
            );

            if ($distance <= 100) {

                $stopTime->update([
                    'status' => 'arrived',
                    'actual_arrival_time' => now(),
                    'delay_minutes' => now()->diffInMinutes(
                        $stopTime->arrival_time,
                        false
                    ),
                ]);
            }
        }

        $destination = $schedule->route->stops
            ->sortByDesc('order')
            ->first();

        if ($destination) {

            $distanceToDestination = $this->haversine(
                $request->latitude,
                $request->longitude,
                $destination->lat,
                $destination->lng
            );

            if ($distanceToDestination <= 100) {

                $schedule->update([
                    'status' => 'completed'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'location' => $location,
                'schedule_status' => $schedule->status,
            ]
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

        $schedule = Schedule::with([
            'route.stops',
            'stopTimes.stop'
        ])->findOrFail($scheduleId);

        $location = Location::where('schedule_id', $scheduleId)
            ->latest('recorded_at')
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No tracking data yet'
            ], 404);
        }

        $nextStop = $schedule->stopTimes
            ->where('status', '!=', 'arrived')
            ->sortBy('stop_order')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'accuracy' => $location->accuracy,
                'recorded_at' => $location->recorded_at,
                'schedule_status' => $schedule->status,
                'next_stop' => $nextStop ? [
                    'name' => $nextStop->stop->name,
                    'arrival_time' => $nextStop->arrival_time,
                    'status' => $nextStop->status,
                ] : null,
            ]
        ]);
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) *
            sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth * $c;
    }
}