<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    
public function index()
{
    $title = 'Drivers || Admin Gassin!';
    $navtitle = 'Drivers';
    $drivers = Driver::with(['user', 'documents'])
        ->latest()
        ->paginate(10);

    return view('pages.drivers.index', compact('title', 'navtitle', 'drivers'));
}
}
