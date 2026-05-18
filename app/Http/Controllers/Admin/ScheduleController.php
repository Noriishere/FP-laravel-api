<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleStopTimes as ScheduleStopTime;
use App\Models\Seat;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $navtitle = 'Schedules';
        $title = 'Schedules || Admin Gassin!';
        $schedules = Schedule::with([
            'route.stops',
            'driver.user',
            'vehicle',
            'stopTimes.stop',
        ])
            ->latest()
            ->get();

        return view(
            'pages.schedules.index',
            compact('schedules', 'navtitle', 'title')
        );
    }

    public function edit($id)
    {
        $navtitle = 'Edit Schedule';
        $title = 'Edit Schedule || Admin Gassin!';

        $schedule = Schedule::with([
            'route.stops',
            'driver.user',
            'vehicle',
        ])->findOrFail($id);

        if (in_array($schedule->status, ['on-going', 'completed'])) {

            return redirect()
                ->route('schedules.index')
                ->with(
                    'error',
                    'Jadwal yang sedang berjalan atau selesai tidak dapat diedit.'
                );
        }

        $routes = Route::with([
            'stops',
        ])->where(
            'is_active',
            true
        )->get();

        return view(
            'pages.schedules.edit',
            compact(
                'schedule',
                'routes',
                'navtitle',
                'title'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::with([
            'route.stops',
            'stopTimes',
            'bookings',
            'vehicle',
        ])->findOrFail($id);

        if (in_array($schedule->status, ['on-going', 'completed'])) {

            return redirect()
                ->route('schedules.index')
                ->with(
                    'error',
                    'Jadwal yang sedang berjalan atau selesai tidak dapat diupdate.'
                );
        }

        $request->validate([

            'route_id' => 'required|exists:routes,id',

            'driver_id' => 'required|exists:drivers,id',

            'vehicle_id' => 'required|exists:vehicles,id',

            'departure_time' => 'required|date',

            'duration' => 'required|integer|min:1',

            'price' => 'required|numeric|min:0',
        ]);

        $route = Route::with([
            'stops',
        ])->findOrFail(
            $request->route_id
        );

        if ($route->stops->count() < 2) {

            return back()->withErrors([
                'route' => 'Route minimal harus memiliki 2 stop',
            ]);
        }

        $start = Carbon::parse(
            $request->departure_time
        );

        $end = $start->copy()
            ->addMinutes(
                (int) $request->duration
            );

        /*
        |--------------------------------------------------------------------------
        | Driver Conflict
        |--------------------------------------------------------------------------
        */

        $driverBusy = Schedule::where(
            'driver_id',
            $request->driver_id
        )
            ->where('id', '!=', $schedule->id)
            ->where(function ($q) use (
                $start,
                $end
            ) {

                $q->whereBetween(
                    'departure_time',
                    [$start, $end]
                )
                    ->orWhereBetween(
                        'arrival_time',
                        [$start, $end]
                    )
                    ->orWhere(function ($q2) use (
                        $start,
                        $end
                    ) {

                        $q2->where(
                            'departure_time',
                            '<=',
                            $start
                        )
                            ->where(
                                'arrival_time',
                                '>=',
                                $end
                            );
                    });
            })
            ->exists();

        if ($driverBusy) {

            return back()->withErrors([
                'driver' => 'Driver sedang digunakan di waktu tersebut',
            ])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Vehicle Conflict
        |--------------------------------------------------------------------------
        */

        $vehicleBusy = Schedule::where(
            'vehicle_id',
            $request->vehicle_id
        )
            ->where('id', '!=', $schedule->id)
            ->where(function ($q) use (
                $start,
                $end
            ) {

                $q->whereBetween(
                    'departure_time',
                    [$start, $end]
                )
                    ->orWhereBetween(
                        'arrival_time',
                        [$start, $end]
                    )
                    ->orWhere(function ($q2) use (
                        $start,
                        $end
                    ) {

                        $q2->where(
                            'departure_time',
                            '<=',
                            $start
                        )
                            ->where(
                                'arrival_time',
                                '>=',
                                $end
                            );
                    });
            })
            ->exists();

        if ($vehicleBusy) {

            return back()->withErrors([
                'vehicle' => 'Kendaraan sedang digunakan di waktu tersebut',
            ])->withInput();
        }

        DB::transaction(function () use (
            $request,
            $schedule,
            $route,
            $start,
            $end
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Schedule
            |--------------------------------------------------------------------------
            */

            $schedule->update([

                'route_id' => $route->id,

                'driver_id' => $request->driver_id,

                'vehicle_id' => $request->vehicle_id,

                'departure_time' => $start,

                'arrival_time' => $end,

                'estimated_duration' => $request->duration,

                'price' => $request->price,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Stop Times
            |--------------------------------------------------------------------------
            */

            $schedule->stopTimes()->delete();

            $stopCount = $route->stops->count();

            $durationPerSegment =
                $request->duration
                / max(1, ($stopCount - 1));

            foreach (
                $route->stops as $index => $stop
            ) {

                $arrival =
                    $start->copy()
                        ->addMinutes(
                            floor(
                                $durationPerSegment * $index
                            )
                        );

                $departure =
                    $arrival->copy()->addMinutes(5);

                if ($index === 0) {
                    $arrival = null;
                }

                if ($index === $stopCount - 1) {
                    $departure = null;
                }

                ScheduleStopTime::create([

                    'schedule_id' => $schedule->id,

                    'route_stop_id' => $stop->id,

                    'arrival_time' => $arrival,

                    'departure_time' => $departure,

                    'status' => 'pending',

                    'stop_order' => $stop->order,

                    'delay_minutes' => 0,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Regenerate Seats Jika Vehicle Berubah
            |--------------------------------------------------------------------------
            */

            if (
                $schedule->vehicle_id != $request->vehicle_id
            ) {

                $schedule->seats()->delete();

                $vehicle = Vehicle::findOrFail(
                    $request->vehicle_id
                );

                $seats = [];

                for (
                    $i = 1;
                    $i <= $vehicle->capacity;
                    $i++
                ) {

                    $seats[] = [
                        'schedule_id' => $schedule->id,
                        'seat_number' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Seat::insert($seats);
            }
        });

        return redirect()
            ->route('schedules.index')
            ->with(
                'success',
                'Schedule berhasil diupdate'
            );
    }

    public function create()
    {
        $navtitle = 'Create Schedule';
        $title = 'Create Schedule || Admin Gassin!';
        $routes = Route::with([
            'stops',
        ])->where(
            'is_active',
            true
        )->get();

        return view(
            'pages.schedules.create',
            compact('routes', 'navtitle', 'title')
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'route_id' => 'required|exists:routes,id',

            'driver_id' => 'required|exists:drivers,id',

            'vehicle_id' => 'required|exists:vehicles,id',

            'departure_time' => 'required|date',

            'duration' => 'required|integer|min:1',

            'price' => 'required|numeric|min:0',
        ]);

        $route = Route::with([
            'stops',
        ])->findOrFail(
            $request->route_id
        );

        if ($route->stops->count() < 2) {

            return back()->withErrors([
                'route' => 'Route minimal harus memiliki 2 stop',
            ]);
        }

        $start = Carbon::parse(
            $request->departure_time
        );

        $end = $start->copy()
            ->addMinutes(
                (int) $request->duration
            );

        /*
    |--------------------------------------------------------------------------
    | Driver Conflict
    |--------------------------------------------------------------------------
    */

        $driverBusy = Schedule::where(
            'driver_id',
            $request->driver_id
        )
            ->where(function ($q) use (
                $start,
                $end
            ) {

                $q->whereBetween(
                    'departure_time',
                    [$start, $end]
                )
                    ->orWhereBetween(
                        'arrival_time',
                        [$start, $end]
                    )
                    ->orWhere(function ($q2) use (
                        $start,
                        $end
                    ) {

                        $q2->where(
                            'departure_time',
                            '<=',
                            $start
                        )
                            ->where(
                                'arrival_time',
                                '>=',
                                $end
                            );
                    });
            })
            ->exists();

        if ($driverBusy) {

            return back()->withErrors([
                'driver' => 'Driver sedang digunakan di waktu tersebut',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Vehicle Conflict
    |--------------------------------------------------------------------------
    */

        $vehicleBusy = Schedule::where(
            'vehicle_id',
            $request->vehicle_id
        )
            ->where(function ($q) use (
                $start,
                $end
            ) {

                $q->whereBetween(
                    'departure_time',
                    [$start, $end]
                )
                    ->orWhereBetween(
                        'arrival_time',
                        [$start, $end]
                    )
                    ->orWhere(function ($q2) use (
                        $start,
                        $end
                    ) {

                        $q2->where(
                            'departure_time',
                            '<=',
                            $start
                        )
                            ->where(
                                'arrival_time',
                                '>=',
                                $end
                            );
                    });
            })
            ->exists();

        if ($vehicleBusy) {

            return back()->withErrors([
                'vehicle' => 'Kendaraan sedang digunakan di waktu tersebut',
            ]);
        }

        DB::transaction(function () use (
            $request,
            $route,
            $start,
            $end
        ) {

            /*
        |--------------------------------------------------------------------------
        | Create Schedule
        |--------------------------------------------------------------------------
        */

            $schedule = Schedule::create([

                'route_id' => $route->id,

                'driver_id' => $request->driver_id,

                'vehicle_id' => $request->vehicle_id,

                'departure_time' => $start,

                'arrival_time' => $end,

                'estimated_duration' => $request->duration,

                'price' => $request->price,

                'status' => 'scheduled',
            ]);

            /*
        |--------------------------------------------------------------------------
        | Generate Seats
        |--------------------------------------------------------------------------
        */

            $vehicle = Vehicle::findOrFail(
                $request->vehicle_id
            );

            $seats = [];

            for (
                $i = 1;
                $i <= $vehicle->capacity;
                $i++
            ) {

                $seats[] = [
                    'schedule_id' => $schedule->id,
                    'seat_number' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Seat::insert($seats);

            /*
        |--------------------------------------------------------------------------
        | Generate Stop Times
        |--------------------------------------------------------------------------
        */

            $stopCount = $route->stops->count();

            $durationPerSegment =
                $request->duration
                / max(1, ($stopCount - 1));

            foreach (
                $route->stops as $index => $stop
            ) {

                $arrival =
                    $start->copy()
                        ->addMinutes(
                            floor(
                                $durationPerSegment * $index
                            )
                        );

                $departure =
                    $arrival->copy()->addMinutes(5);

                if ($index === 0) {
                    $arrival = null;
                }

                if ($index === $stopCount - 1) {
                    $departure = null;
                }

                ScheduleStopTime::create([

                    'schedule_id' => $schedule->id,

                    'route_stop_id' => $stop->id,

                    'arrival_time' => $arrival,

                    'departure_time' => $departure,

                    'status' => 'pending',

                    'stop_order' => $stop->order,

                    'delay_minutes' => 0,
                ]);
            }
        });

        return redirect()
            ->route('schedules.index')
            ->with(
                'success',
                'Schedule berhasil dibuat'
            );
    }

    public function availableDrivers(Request $request)
    {
        $start = Carbon::parse(
            $request->departure_time
        );

        $end = $start->copy()
            ->addMinutes(
                (int) $request->duration
            );

        $busyDriverIds = Schedule::where(function ($q) use (
            $start,
            $end
        ) {

            $q->whereBetween(
                'departure_time',
                [$start, $end]
            )
                ->orWhereBetween(
                    'arrival_time',
                    [$start, $end]
                )
                ->orWhere(function ($q2) use (
                    $start,
                    $end
                ) {

                    $q2->where(
                        'departure_time',
                        '<=',
                        $start
                    )
                        ->where(
                            'arrival_time',
                            '>=',
                            $end
                        );
                });
        })
            ->pluck('driver_id');

        $drivers = Driver::with([
            'user',
        ])
            ->whereNotIn(
                'id',
                $busyDriverIds
            )
            ->get();

        return response()->json($drivers);
    }

    public function availableVehicles(Request $request)
    {
        $start = Carbon::parse(
            $request->departure_time
        );

        $end = $start->copy()
            ->addMinutes(
                (int) $request->duration
            );

        $busyVehicleIds = Schedule::where(function ($q) use (
            $start,
            $end
        ) {

            $q->whereBetween(
                'departure_time',
                [$start, $end]
            )
                ->orWhereBetween(
                    'arrival_time',
                    [$start, $end]
                )
                ->orWhere(function ($q2) use (
                    $start,
                    $end
                ) {

                    $q2->where(
                        'departure_time',
                        '<=',
                        $start
                    )
                        ->where(
                            'arrival_time',
                            '>=',
                            $end
                        );
                });
        })
            ->pluck('vehicle_id');

        $vehicles = Vehicle::whereNotIn(
            'id',
            $busyVehicleIds
        )->get();

        return response()->json($vehicles);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        if (in_array($schedule->status, ['on-going', 'completed'])) {
            return redirect()->back()
                ->with('error', 'Jadwal yang sedang berjalan atau selesai tidak dapat dihapus.');
        }

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function show($id)
    {
        $navtitle = 'Detail Schedule';
        $title = 'Detail Schedule || Admin Gassin!';
        $schedule = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user',
            'stopTimes.stop',
            'bookings.user',
            'bookings.pickupStop',
            'bookings.dropoffStop',
            'bookings.bookingSeats.seat',
        ])->findOrFail($id);

        $activeBookings = $schedule->bookings
            ->filter(function ($booking) {

                if ($booking->payment_status === 'paid') {
                    return true;
                }

                if (
                    $booking->payment_status === 'pending'
                    &&
                    $booking->expired_at
                    &&
                    $booking->expired_at > now()
                ) {
                    return true;
                }

                return false;
            });

        return view(
            'pages.schedules.show',
            compact(
                'schedule',
                'activeBookings',
                'navtitle',
                'title'
            )
        );
    }
}
