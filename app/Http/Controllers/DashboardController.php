<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
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
        return view('pages.dashboard', compact(
            'totalUsers',
            'totalBookings',
            'activeSchedules',
            'activeVehicles',
            'latestBookings',
            'vehicles'
        ));
    }
}
