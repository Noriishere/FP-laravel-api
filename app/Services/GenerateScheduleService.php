<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleStopTimes;
use App\Models\Seat;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateScheduleService
{
    protected array $times = [
        '07:00',
        '12:00',
        '18:00',
        '21:00',
    ];

    protected int $duration = 120;

    protected int $price = 25000;

    public function generate(Carbon $date): void
    {
        dd(
            Route::count(),
            Driver::count(),
            Vehicle::count()
        );
        $routes = Route::with('stops')
            ->where('is_active', true)
            ->get();

        if ($routes->isEmpty()) {
            return;
        }

        $drivers = Driver::where('verification_status', 'verified')
            ->where('status', 'active')
            ->get();

        $vehicles = Vehicle::all();

        $vehicles = Vehicle::all();

        foreach ($routes as $route) {

            foreach ($this->times as $time) {

                $departure = Carbon::parse(
                    $date->format('Y-m-d').' '.$time
                );

                if (
                    Schedule::where('route_id', $route->id)
                        ->where('departure_time', $departure)
                        ->exists()
                ) {
                    continue;
                }

                $arrival = $departure
                    ->copy()
                    ->addMinutes($this->duration);

                $driver = $this->findAvailableDriver(
                    $drivers,
                    $departure,
                    $arrival
                );

                $vehicle = $this->findAvailableVehicle(
                    $vehicles,
                    $departure,
                    $arrival
                );

                if (! $driver || ! $vehicle) {
                    continue;
                }

                $this->createSchedule(
                    $route,
                    $driver,
                    $vehicle,
                    $departure,
                    $arrival
                );
            }
        }
    }

    protected function createSchedule(
        Route $route,
        Driver $driver,
        Vehicle $vehicle,
        Carbon $departure,
        Carbon $arrival
    ): void {

        DB::transaction(function () use (
            $route,
            $driver,
            $vehicle,
            $departure,
            $arrival
        ) {

            $schedule = Schedule::create([
                'route_id' => $route->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'departure_time' => $departure,
                'arrival_time' => $arrival,
                'estimated_duration' => $this->duration,
                'price' => $this->price,
                'status' => 'scheduled',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Generate Seats
            |--------------------------------------------------------------------------
            */

            $seats = [];

            for ($i = 1; $i <= $vehicle->capacity; $i++) {

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

            $stops = $route->stops;

            $stopCount = $stops->count();

            $segmentDuration =
                $this->duration /
                max(1, ($stopCount - 1));

            foreach ($stops as $index => $stop) {

                $arrivalTime = $departure
                    ->copy()
                    ->addMinutes(
                        floor(
                            $segmentDuration * $index
                        )
                    );

                $departureTime = $arrivalTime
                    ->copy()
                    ->addMinutes(5);

                if ($index === 0) {
                    $arrivalTime = null;
                }

                if ($index === ($stopCount - 1)) {
                    $departureTime = null;
                }

                ScheduleStopTimes::create([
                    'schedule_id' => $schedule->id,
                    'route_stop_id' => $stop->id,
                    'arrival_time' => $arrivalTime,
                    'departure_time' => $departureTime,
                    'status' => 'pending',
                    'stop_order' => $stop->order,
                    'delay_minutes' => 0,
                ]);
            }
        });
    }

    protected function findAvailableDriver(
        $drivers,
        Carbon $start,
        Carbon $end
    ): ?Driver {

        foreach ($drivers as $driver) {

            $busy = Schedule::where('driver_id', $driver->id)
                ->whereIn('status', [
                    'scheduled',
                    'on-going',
                ])
                ->where(function ($query) use ($start, $end) {

                    $query->whereBetween(
                        'departure_time',
                        [$start, $end]
                    )
                        ->orWhereBetween(
                            'arrival_time',
                            [$start, $end]
                        )
                        ->orWhere(function ($q) use ($start, $end) {

                            $q->where(
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

            if (! $busy) {
                return $driver;
            }
        }

        return null;
    }

    protected function findAvailableVehicle(
        $vehicles,
        Carbon $start,
        Carbon $end
    ): ?Vehicle {

        foreach ($vehicles as $vehicle) {

            $busy = Schedule::where('vehicle_id', $vehicle->id)
                ->whereIn('status', [
                    'scheduled',
                    'on-going',
                ])
                ->where(function ($query) use ($start, $end) {

                    $query->whereBetween(
                        'departure_time',
                        [$start, $end]
                    )
                        ->orWhereBetween(
                            'arrival_time',
                            [$start, $end]
                        )
                        ->orWhere(function ($q) use ($start, $end) {

                            $q->where(
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

            if (! $busy) {
                return $vehicle;
            }
        }

        return null;
    }
}
