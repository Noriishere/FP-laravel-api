<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Vehicle;

class AdminLandingController extends Controller
{
    public function index()
    {
        $stats = [
            'bookings' => Booking::count(),
            'schedules' => Schedule::count(),
            'drivers' => Driver::count(),
            'vehicles' => Vehicle::count(),
        ];

        return view('pages.home', compact('stats'));
    }
}