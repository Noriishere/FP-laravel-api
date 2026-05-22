<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Http\Request;

class TripMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Trip Monitoring || Admin Gassin!';

        $navtitle = 'Trip Monitoring';

        $schedules = Schedule::with([

            'route.origin',

            'route.destination',

            'vehicle',

            'driver.user',
        ])
            ->whereDate('departure_time', today())

            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('route', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('driver.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            })

            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })

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