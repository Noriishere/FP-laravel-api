<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['route', 'vehicle', 'driver']);

        if ($request->origin) {
            $query->whereHas(
                'route',
                fn($q) =>
                $q->where('origin_name', 'like', '%' . $request->origin . '%')
            );
        }

        if ($request->destination) {
            $query->whereHas(
                'route',
                fn($q) =>
                $q->where('destination_name', 'like', '%' . $request->destination . '%')
            );
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with([
            'route',
            'vehicle',
            'driver',
            'seats'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }
    public function map($id)
    {
        $schedule = Schedule::with(['route', 'driver'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'route' => [
                    'origin' => [
                        'name' => $schedule->route->origin_name,
                        'lat' => (float) $schedule->route->origin_lat,
                        'lng' => (float) $schedule->route->origin_lng,
                    ],
                    'destination' => [
                        'name' => $schedule->route->destination_name,
                        'lat' => (float) $schedule->route->destination_lat,
                        'lng' => (float) $schedule->route->destination_lng,
                    ],
                    'polyline' => json_decode($schedule->route->polyline),
                ],
                'driver' => [
                    'name' => $schedule->driver?->user?->name,
                ]
            ]
        ]);
    }
}
