<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $origin = strtolower($request->origin ?? '');
        $destination = strtolower($request->destination ?? '');
        $query = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user'
        ]);

        if ($request->origin && $request->destination) {

            $origin = strtolower($request->origin);
            $destination = strtolower($request->destination);

            $query->whereHas('route', function ($routeQuery) use (
                $origin,
                $destination
            ) {

                $routeQuery
                    ->whereHas('stops', function ($q) use ($origin) {

                        $q->where(
                            'name',
                            'like',
                            "%{$origin}%"
                        )->where('is_pickup', true);
                    })
                    ->whereHas('stops', function ($q) use ($destination) {

                        $q->where(
                            'name',
                            'like',
                            "%{$destination}%"
                        )->where('is_dropoff', true);
                    });
            });
        } elseif ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->origin . '%'
                )->where('is_pickup', true);
            });
        } elseif ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->destination . '%'
                )->where('is_dropoff', true);
            });
        }

        $schedules = $query->get();
        
        if ($request->origin && $request->destination) {

            $schedules = $schedules->filter(function ($schedule) use (
                $origin,
                $destination
            ) {

                $originStop = $schedule->route->stops
                    ->filter(function ($stop) use ($origin) {

                        return str_contains(
                            strtolower($stop->name),
                            $origin
                        );
                    })
                    ->first();

                $destinationStop = $schedule->route->stops
                    ->filter(function ($stop) use ($destination) {

                        return str_contains(
                            strtolower($stop->name),
                            $destination
                        );
                    })
                    ->first();

                if (!$originStop || !$destinationStop) {
                    return false;
                }

                return $originStop->order < $destinationStop->order;
            })->values();
        }

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user',
            'stopTimes.stop'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function map($id)
    {
        $schedule = Schedule::with([
            'route.stops',
            'driver.user',
            'stopTimes.stop'
        ])->findOrFail($id);

        $origin = $schedule->route->origin;
        $destination = $schedule->route->destination;

        return response()->json([
            'success' => true,
            'data' => [

                'route' => [

                    'name' => $schedule->route->name,

                    'origin' => [
                        'name' => $origin?->name,
                        'lat' => (float) $origin?->lat,
                        'lng' => (float) $origin?->lng,
                    ],

                    'destination' => [
                        'name' => $destination?->name,
                        'lat' => (float) $destination?->lat,
                        'lng' => (float) $destination?->lng,
                    ],

                    'stops' => $schedule->route->stops,

                    'stop_times' => $schedule->stopTimes,

                    'polyline' => json_decode(
                        $schedule->route->polyline
                    ),
                ],

                'driver' => [
                    'name' => $schedule->driver?->user?->name,
                ]
            ]
        ]);
    }

    public function sorted(Request $request)
    {
        $query = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user'
        ]);

        if ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->origin . '%'
                )->where('is_pickup', true);
            });
        }

        if ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->destination . '%'
                )->where('is_dropoff', true);
            });
        }

        $direction = $request->get('direction', 'asc');

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy('departure_time', $direction);

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function sortedByDay(Request $request)
    {
        $query = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user'
        ]);

        if ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->origin . '%'
                )->where('is_pickup', true);
            });
        }

        if ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->destination . '%'
                )->where('is_dropoff', true);
            });
        }

        if ($request->origin_date) {

            $start = Carbon::parse($request->origin_date)
                ->startOfDay();

            $end = Carbon::parse($request->origin_date)
                ->endOfDay();

            $query->whereBetween(
                'departure_time',
                [$start, $end]
            );
        }

        if ($request->destination_date) {

            $start = Carbon::parse($request->destination_date)
                ->startOfDay();

            $end = Carbon::parse($request->destination_date)
                ->endOfDay();

            $query->whereBetween(
                'arrival_time',
                [$start, $end]
            );
        }

        if ($request->from_date && $request->to_date) {

            $start = Carbon::parse($request->from_date)
                ->startOfDay();

            $end = Carbon::parse($request->to_date)
                ->endOfDay();

            $query->whereBetween(
                'departure_time',
                [$start, $end]
            );
        }

        $direction = $request->get('direction', 'asc');

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy('departure_time', $direction);

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }
}
