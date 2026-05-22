<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Schedule;

class TripMonitoringController extends Controller
{
    public function index()
    {
        $title = 'Trip Monitoring || Admin Gassin!';

        $navtitle = 'Trip Monitoring';

        $schedules = Schedule::with([

            'route.origin',

            'route.destination',

            'vehicle',

            'driver.user',
        ])
            ->whereDate(
                'departure_time',
                today()
            )
            ->latest()
            ->get();

        return view(

            'pages.trip-monitoring.index',

            compact(
                'schedules',
                'title',
                'navtitle'
            )
        );
    }

    public function show($id)
    {
        $title = 'Trip Detail || Admin Gassin!';

        $navtitle = 'Trip Detail';

        $schedule = Schedule::findOrFail($id);

        return view(

            'pages.trip-monitoring.show',

            compact(
                'schedule',
                'title',
                'navtitle'
            )
        );
    }

    public function trackingData($id)
    {
        $location = Location::with([

            'schedule.route.stops',

            'schedule.stopTimes.stop',

            'schedule.vehicle',

            'schedule.driver.user',
        ])
            ->where(
                'schedule_id',
                $id
            )
            ->first();

        if (! $location) {

            return response()->json([

                'success' => false,

                'message' => 'Tracking unavailable',
            ]);
        }

        return response()->json([

            'success' => true,

            'data' => $location,
        ]);
    }
}
