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
            'driver.user',
            'seats'
        ])->findOrFail($id);

        // ambil seat yang sudah dibooking (PAID + PENDING aktif)
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
    public function sorted(Request $request)
    {
        $query = Schedule::with(['route', 'vehicle', 'driver']);

        // optional filter (biar tetep bisa dipakai kayak index)
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

        // sorting
        $direction = $request->get('direction', 'asc'); // asc / desc

        $query->orderBy('departure_time', $direction);

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }
    public function sortedByDay(Request $request)
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

        // 🔥 FILTER TANGGAL (1 hari)
        if ($request->date) {
            $start = Carbon::parse($request->date)->startOfDay();
            $end = Carbon::parse($request->date)->endOfDay();

            $query->whereBetween('departure_time', [$start, $end]);
        }

        if ($request->from_date && $request->to_date) {
            $start = Carbon::parse($request->from_date)->startOfDay();
            $end = Carbon::parse($request->to_date)->endOfDay();

            $query->whereBetween('departure_time', [$start, $end]);
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
