<?php

namespace App\Services;

use App\Mail\DriverAssignmentMail;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleStopTimes as ScheduleStopTime;
use App\Models\Seat;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        dump("Mulai proses generate untuk tanggal: " . $date->format('Y-m-d'));

        $routes = Route::with('stops')->where('is_active', true)->get();
        dump("Jumlah rute aktif ditemukan: " . $routes->count());

        if ($routes->isEmpty()) {
            dump("BERHENTI TOTAL: Tidak ada rute aktif di database.");
            return;
        }

        foreach ($routes as $route) {
            dump("Mengecek Rute ID: " . $route->id . " | Jumlah Titik Pemberhentian (Stops): " . $route->stops->count());

            if ($route->stops->count() < 2) {
                dump("  -> SKIP Rute ID " . $route->id . ": Jumlah stop kurang dari 2.");
                continue;
            }

            foreach ($this->times as $time) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $time);
                $end = $start->copy()->addMinutes($this->duration);

                dump("  Mengecek jadwal jam keberangkatan: " . $time);

                $scheduleExists = Schedule::where('route_id', $route->id)
                    ->where('departure_time', $start)
                    ->exists();

                if ($scheduleExists) {
                    dump("    -> SKIP Jam " . $time . ": Jadwal untuk rute dan jam ini sudah ada.");
                    continue;
                }

                $driver = $this->findAvailableDriver($start, $end);
                if (!$driver) {
                    dump("    -> SKIP Jam " . $time . ": Tidak ada sopir dengan status 'verified' yang sedang menganggur.");
                }

                $vehicle = $this->findAvailableVehicle($start, $end);
                if (!$vehicle) {
                    dump("    -> SKIP Jam " . $time . ": Tidak ada kendaraan yang sedang menganggur.");
                }

                if (! $driver || ! $vehicle) {
                    continue;
                }

                dump("    -> BERHASIL! Membuat jadwal untuk Jam " . $time . " (Sopir ID: " . $driver->id . ", Kendaraan ID: " . $vehicle->id . ")");

                $this->createSchedule(
                    $route,
                    $driver,
                    $vehicle,
                    $start,
                    $end
                );
            }
        }
        
        dump("Proses loop generate selesai.");
    }

    protected function createSchedule(
        Route $route,
        Driver $driver,
        Vehicle $vehicle,
        Carbon $start,
        Carbon $end
    ): void {
        $schedule = null;

        DB::transaction(function () use (
            $route,
            $driver,
            $vehicle,
            $start,
            $end,
            &$schedule
        ) {
            $schedule = Schedule::create([
                'route_id' => $route->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'departure_time' => $start,
                'arrival_time' => $end,
                'estimated_duration' => $this->duration,
                'price' => $this->price,
                'status' => 'scheduled',
            ]);

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

            $stopCount = $route->stops->count();
            $durationPerSegment = $this->duration / max(1, ($stopCount - 1));

            foreach ($route->stops as $index => $stop) {
                $arrival = $start->copy()->addMinutes(floor($durationPerSegment * $index));
                $departure = $arrival->copy()->addMinutes(5);

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

        if ($schedule) {
            try {
                Mail::to($driver->user->email)->send(new DriverAssignmentMail($schedule));
            } catch (\Throwable $e) {
                Log::error('Driver assignment email failed during auto-generation', [
                    'schedule_id' => $schedule->id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    protected function findAvailableDriver(Carbon $start, Carbon $end): ?Driver
    {
        $busyDriverIds = Schedule::whereIn('status', ['scheduled', 'on-going'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('departure_time', [$start, $end])
                    ->orWhereBetween('arrival_time', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('departure_time', '<=', $start)
                            ->where('arrival_time', '>=', $end);
                    });
            })
            ->pluck('driver_id');

        return Driver::with('user')
            ->where('verification_status', 'verified')
            ->whereNotIn('id', $busyDriverIds)
            ->first();
    }

    protected function findAvailableVehicle(Carbon $start, Carbon $end): ?Vehicle
    {
        $busyVehicleIds = Schedule::whereIn('status', ['scheduled', 'on-going'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('departure_time', [$start, $end])
                    ->orWhereBetween('arrival_time', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('departure_time', '<=', $start)
                            ->where('arrival_time', '>=', $end);
                    });
            })
            ->pluck('vehicle_id');

        return Vehicle::whereNotIn('id', $busyVehicleIds)->first();
    }
}