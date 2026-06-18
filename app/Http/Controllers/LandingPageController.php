<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;

class LandingPageController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'drivers' => Driver::count(),
            'routes' => Route::count(),
            'vehicles' => Vehicle::count(),
            'bookings' => Booking::count(),
        ];

        return view('pages.landing-pages', compact('stats'));
    }
}
