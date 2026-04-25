<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $bookingStats = DB::table('bookings')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $labels = $bookingStats->pluck('date');
        $data = $bookingStats->pluck('total');
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $activeSchedules = Schedule::where('status', 'on-going')->count();
        $activeVehicles = Vehicle::count();

        $latestBookings = Booking::with(['user', 'schedule.route'])
            ->latest()
            ->take(5)
            ->get();

        $vehicles = Vehicle::with('schedules')->latest()->take(5)->get();
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $todayRevenue = Booking::sum('total_price');
        $nextSchedule = Schedule::with('route')
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
            'data'
        ));
    }
}
