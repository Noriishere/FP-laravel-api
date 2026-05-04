<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Seat;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['route', 'driver.user', 'vehicle'])
            ->latest()
            ->get();

        return view('pages.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $routes = Route::all();

        return view('pages.schedules.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'departure_time' => 'required|date',
            'duration' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        $start = Carbon::parse($request->departure_time);
        $end = $start->copy()->addMinutes((int) $request->duration);

        $driverBusy = Schedule::where('driver_id', $request->driver_id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('departure_time', [$start, $end])
                    ->orWhereBetween('arrival_time', [$start, $end]);
            })
            ->exists();

        if ($driverBusy) {
            return back()->withErrors('Driver sedang digunakan di waktu tersebut');
        }

        $vehicleBusy = Schedule::where('vehicle_id', $request->vehicle_id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('departure_time', [$start, $end])
                    ->orWhereBetween('arrival_time', [$start, $end]);
            })
            ->exists();

        if ($vehicleBusy) {
            return back()->withErrors('Kendaraan sedang digunakan di waktu tersebut');
        }

        $schedule = Schedule::create([
            'route_id' => $request->route_id,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'departure_time' => $start,
            'arrival_time' => $end,
            'price' => $request->price,
            'status' => 'scheduled'
        ]);
        $vehicle = Vehicle::find($request->vehicle_id);

        for ($i = 1; $i <= $vehicle->capacity; $i++) {
            Seat::create([
                'schedule_id' => $schedule->id,
                'seat_number' => $i
            ]);
        }
        return redirect()->route('schedules.index')
            ->with('success', 'Schedule berhasil dibuat');
    }

    public function availableDrivers(Request $request)
    {
        $start = Carbon::parse($request->departure_time);
        $duration = (int) $request->duration;

        $start = Carbon::parse($request->departure_time);
        $end = $start->copy()->addMinutes((int) $request->duration);

        $busyDriverIds = Schedule::where(function ($q) use ($start, $end) {
            $q->whereBetween('departure_time', [$start, $end])
                ->orWhereBetween('arrival_time', [$start, $end]);
        })->pluck('driver_id');

        $drivers = Driver::with('user')
            ->whereNotIn('id', $busyDriverIds)
            ->get();

        return response()->json($drivers);
    }

    public function availableVehicles(Request $request)
    {
        $start = Carbon::parse($request->departure_time);
        $duration = (int) $request->duration;

        $start = Carbon::parse($request->departure_time);
        $end = $start->copy()->addMinutes((int) $request->duration);

        $busyVehicleIds = Schedule::where(function ($q) use ($start, $end) {
            $q->whereBetween('departure_time', [$start, $end])
                ->orWhereBetween('arrival_time', [$start, $end]);
        })->pluck('vehicle_id');

        $vehicles = Vehicle::whereNotIn('id', $busyVehicleIds)->get();

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
        $schedule = Schedule::with([
            'route',
            'vehicle',
            'driver.user'
        ])->findOrFail($id);

        // ambil seat yang sudah dibooking
        $bookedSeatIds = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.schedule_id', $id)
            ->where(function ($q) {
                $q->where('bookings.payment_status', 'paid')
                    ->orWhere(function ($q2) {
                        $q2->where('bookings.payment_status', 'pending')
                            ->where('bookings.expired_at', '>', now());
                    });
            })
            ->pluck('booking_seats.seat_id')
            ->toArray();

        return view('pages.schedules.show', compact('schedule', 'bookedSeatIds'));
    }
}
