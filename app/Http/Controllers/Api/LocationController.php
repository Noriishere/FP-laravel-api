<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function start($id)
    {
        $driver = auth('api')->user()?->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 403);
        }

        $schedule = Schedule::with([
            'route.origin',
            'route.destination',
            'vehicle',
        ])
            ->where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

        if (! $schedule) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized schedule',
            ], 403);
        }

        if ($schedule->status === 'completed') {

            return response()->json([
                'success' => false,
                'message' => 'Schedule already completed',
            ], 400);
        }

        if ($schedule->status === 'on-going') {

            return response()->json([
                'success' => false,
                'message' => 'Schedule already started',
            ], 400);
        }

        $now = now();

        $departureTime = Carbon::parse(
            $schedule->departure_time
        );

        $allowedStartTime = $departureTime
            ->copy()
            ->subMinutes(30);

        if ($now->lt($allowedStartTime)) {

            return response()->json([
                'success' => false,
                'message' => 'Trip cannot be started yet',
                'data' => [
                    'current_time' => $now,
                    'can_start_at' => $allowedStartTime,
                    'departure_time' => $departureTime,
                ],
            ], 400);
        }

        $schedule->update([
            'status' => 'on-going',
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Trip started successfully',

            'data' => [

                'schedule_id' => $schedule->id,

                'status' => $schedule->status,

                'departure_time' => $schedule->departure_time,

                'arrival_time' => $schedule->arrival_time,

                'started_at' => $now,

                'vehicle' => [

                    'id' => $schedule->vehicle?->id,

                    'name' => $schedule->vehicle?->name,

                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'route' => [

                    'id' => $schedule->route?->id,

                    'name' => $schedule->route?->name,

                    'origin' => $schedule->route?->origin?->name,

                    'destination' => $schedule->route?->destination?->name,
                ],
            ],
        ]);
    }

    public function update(
        Request $request,
        $id
    ) {

        $request->validate([

            'latitude' => 'required|numeric',

            'longitude' => 'required|numeric',

            'speed' => 'nullable|numeric',

            'heading' => 'nullable|numeric',

            'accuracy' => 'nullable|numeric',

            'is_mocked' => 'nullable|boolean',
        ]);

        $driver = auth('api')->user()?->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 403);
        }

        $schedule = Schedule::with([
            'route.stops',
            'stopTimes.stop',
        ])
            ->where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

        if (! $schedule) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized schedule',
            ], 403);
        }

        if ($schedule->status !== 'on-going') {

            return response()->json([
                'success' => false,
                'message' => 'Schedule not active',
            ], 400);
        }

        $last = Location::where(
            'schedule_id',
            $schedule->id
        )
            ->latest('recorded_at')
            ->first();

        $location = Location::create([

            'schedule_id' => $schedule->id,

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

                    'delay_minutes' => now()
                        ->diffInMinutes(
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
                    'status' => 'completed',
                ]);
            }
        }

        $nextStop = $schedule->stopTimes
            ->where('status', '!=', 'arrived')
            ->sortBy(function ($item) {
                return $item->stop?->order;
            })
            ->first();

        $remainingDistance = null;

        if ($nextStop) {

            $remainingDistance = round(

                $this->haversine(

                    $location->latitude,

                    $location->longitude,

                    $nextStop->stop->lat,

                    $nextStop->stop->lng
                ) / 1000,

                2
            );
        }

        return response()->json([

            'success' => true,

            'message' => 'Location updated',

            'data' => [

                'location' => [

                    'latitude' => $location->latitude,

                    'longitude' => $location->longitude,

                    'speed' => $location->speed,

                    'heading' => $location->heading,

                    'accuracy' => $location->accuracy,

                    'recorded_at' => $location->recorded_at,
                ],

                'schedule' => [

                    'id' => $schedule->id,

                    'status' => $schedule->status,
                ],

                'next_stop' => $nextStop
                ? [

                    'id' => $nextStop->stop?->id,

                    'name' => $nextStop->stop?->name,

                    'latitude' => $nextStop->stop?->lat,

                    'longitude' => $nextStop->stop?->lng,

                    'order' => $nextStop->stop?->order,

                    'arrival_time' => $nextStop->arrival_time,

                    'actual_arrival_time' => $nextStop->actual_arrival_time,

                    'status' => $nextStop->status,

                    'remaining_distance_km' => $remainingDistance,

                    'estimated_remaining_minutes' => $location->speed > 0
                            ? round(
                                (
                                    ($remainingDistance * 1000)
                                    /
                                    ($location->speed * 1000 / 3600)
                                ) / 60
                            )
                            : null,
                ]
                : null,
            ],
        ]);
    }

    public function tracking($scheduleId)
    {
        $hasBooking = Booking::where(
            'user_id',
            auth('api')->id()
        )
            ->where('schedule_id', $scheduleId)
            ->exists();

        if (! $hasBooking) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $schedule = Schedule::with([

            'route.origin',
            'route.destination',

            'route.stops',

            'stopTimes.stop',

            'vehicle',

            'driver.user',
        ])
            ->findOrFail($scheduleId);

        $location = Location::where(
            'schedule_id',
            $scheduleId
        )
            ->latest('recorded_at')
            ->first();

        if (! $location) {

            return response()->json([

                'success' => true,

                'message' => 'Tracking not available yet',

                'data' => [

                    'schedule' => [
                        'id' => $schedule->id,
                        'status' => $schedule->status,
                    ],

                    'location' => null,
                ],
            ]);
        }

        $routeStops = $schedule->route->stops
            ->sortBy('order')
            ->map(function ($stop) use (
                $schedule,
                $location
            ) {

                $stopTime = $schedule->stopTimes
                    ->firstWhere(
                        'stop_id',
                        $stop->id
                    );

                $distance = $this->haversine(

                    $location->latitude,

                    $location->longitude,

                    $stop->lat,

                    $stop->lng
                );

                return [

                    'id' => $stop->id,

                    'name' => $stop->name,

                    'latitude' => $stop->lat,

                    'longitude' => $stop->lng,

                    'order' => $stop->order,

                    'distance_km' => round(
                        $distance / 1000,
                        2
                    ),

                    'status' => $stopTime?->status,

                    'arrival_time' => $stopTime?->arrival_time,

                    'actual_arrival_time' => $stopTime?->actual_arrival_time,
                ];
            })
            ->values();

        $nextStop = $schedule->stopTimes
            ->where('status', '!=', 'arrived')
            ->sortBy(function ($item) {
                return $item->stop?->order;
            })
            ->first();

        return response()->json([

            'success' => true,

            'data' => [

                'schedule' => [

                    'id' => $schedule->id,

                    'status' => $schedule->status,

                    'departure_time' => $schedule->departure_time,

                    'arrival_time' => $schedule->arrival_time,

                    'vehicle' => [

                        'name' => $schedule->vehicle?->name,

                        'plate_number' => $schedule->vehicle?->plate_number,
                    ],

                    'driver' => [

                        'name' => $schedule->driver?->user?->name,
                    ],
                ],

                'route' => [

                    'name' => $schedule->route?->name,

                    'stops' => $routeStops,

                    'origin' => [
                        'name' => $schedule->route?->origin?->name,
                    ],

                    'destination' => [
                        'name' => $schedule->route?->destination?->name,
                    ],

                    'polyline' => json_decode(
                        $schedule->route?->polyline
                    ),
                ],

                'location' => [

                    'latitude' => $location->latitude,

                    'longitude' => $location->longitude,

                    'speed' => $location->speed,

                    'heading' => $location->heading,

                    'accuracy' => $location->accuracy,

                    'recorded_at' => $location->recorded_at,
                ],

                'next_stop' => $nextStop
                    ? [

                        'name' => $nextStop->stop?->name,

                        'arrival_time' => $nextStop->arrival_time,

                        'status' => $nextStop->status,
                    ]
                    : null,
            ],
        ]);
    }

    public function stop($id)
    {
        $driver = auth('api')->user()?->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 403);
        }

        $schedule = Schedule::with([
            'route.origin',
            'route.destination',
            'vehicle',
        ])
            ->where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

        if (! $schedule) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized schedule',
            ], 403);
        }

        if ($schedule->status === 'scheduled') {

            return response()->json([
                'success' => false,
                'message' => 'Trip has not started yet',
            ], 400);
        }

        if ($schedule->status === 'completed') {

            return response()->json([
                'success' => false,
                'message' => 'Trip already completed',
            ], 400);
        }

        $now = now();

        $schedule->update([
            'status' => 'completed',
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Trip completed successfully',

            'data' => [

                'schedule_id' => $schedule->id,

                'status' => $schedule->status,

                'departure_time' => $schedule->departure_time,

                'arrival_time' => $schedule->arrival_time,

                'completed_at' => $now,

                'vehicle' => [

                    'id' => $schedule->vehicle?->id,

                    'name' => $schedule->vehicle?->name,

                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'route' => [

                    'id' => $schedule->route?->id,

                    'name' => $schedule->route?->name,

                    'origin' => $schedule->route?->origin?->name,

                    'destination' => $schedule->route?->destination?->name,
                ],
            ],
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
