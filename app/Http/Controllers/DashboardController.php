<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $period = collect(range(0, 29))->map(
            fn($i) =>
            now()->subDays($i)->format('Y-m-d')
        )->reverse();

        $bookingStats = DB::table('bookings')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = $period;
        $data = $period->map(fn($date) => $bookingStats[$date] ?? 0);
        $labels = $labels->values()->toArray();
        $data = $data->values()->toArray();
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $activeSchedules = Schedule::where('status', 'on-going')->count();

        $activeVehicles = Vehicle::whereHas('schedules', function ($q) {
            $q->where('status', 'on-going');
        })->count();

        $latestBookings = Booking::with(['user', 'schedule.route'])
            ->latest()
            ->take(5)
            ->get();
        $onlineDrivers = Driver::with('user')
            ->where('status', 'online')
            ->get();

        $pendingDrivers = Driver::with('user')
            ->where('status', 'pending')
            ->get();
        $vehicles = Vehicle::with('schedules')->latest()->take(5)->get();

        $todayBookings = Booking::whereDate('created_at', today())->count();

        $todayRevenue = Booking::whereDate('created_at', today())
            ->sum('total_price');
        $title = 'Dashboard';
        $nextSchedule = Schedule::with(['route.stops'])
            ->where('departure_time', '>=', now())
            ->orderBy('departure_time')
            ->first();
        return view('pages.dashboard', compact(
            'totalUsers',
            'totalBookings',
            'activeSchedules',
            'activeVehicles',
            'latestBookings',
            'vehicles',
            'nextSchedule',
            'labels',
            'data',
            'todayBookings',
            'todayRevenue',
            'onlineDrivers',
            'pendingDrivers',
            'title'
        ));
    }
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        return view('pages.users', compact('users'));
    }
}
