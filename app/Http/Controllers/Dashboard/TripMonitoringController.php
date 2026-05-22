<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Location;

class TripMonitoringController extends Controller
{
    public function index()
    {
        $title = 'Trip Monitoring || Admin Gassin!';

        $navtitle = 'Trip Monitoring';

        return view(
            'pages.trip-monitoring.index',
            compact(
                'title',
                'navtitle'
            )
        );
    }

    public function data()
    {
        $locations = Location::with([

            'schedule.route.origin',

            'schedule.route.destination',

            'schedule.vehicle',

            'schedule.driver.user',
        ])
            ->whereHas('schedule', function ($q) {

                $q->where(
                    'status',
                    'on-going'
                );
            })
            ->latest('recorded_at')
            ->get();

        $data = $locations->map(function ($location) {

            $schedule = $location->schedule;

            return [

                'schedule_id' => $schedule?->id,

                'status' => $schedule?->status,

                'departure_time' => $schedule?->departure_time,

                'arrival_time' => $schedule?->arrival_time,

                'recorded_at' => $location->recorded_at,

                'latitude' => $location->latitude,

                'longitude' => $location->longitude,

                'speed' => $location->speed,

                'heading' => $location->heading,

                'accuracy' => $location->accuracy,

                'route' => [

                    'name' => $schedule?->route?->name,

                    'origin' => [
                        'name' => $schedule?->route?->origin?->name,
                    ],

                    'destination' => [
                        'name' => $schedule?->route?->destination?->name,
                    ],
                ],

                'vehicle' => [

                    'name' => $schedule?->vehicle?->name,

                    'plate_number' => $schedule?->vehicle?->plate_number,
                ],

                'driver' => [

                    'name' => $schedule?->driver?->user?->name,
                ],

                'tracking_status' => now()
                    ->diffInMinutes(
                        $location->recorded_at
                    ) >= 2
                        ? 'offline'
                        : 'live',
            ];
        });

        return response()->json([

            'success' => true,

            'data' => $data,
        ]);
    }
}