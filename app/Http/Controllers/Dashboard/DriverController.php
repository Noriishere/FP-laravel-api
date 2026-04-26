<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    public function index()
    {
        $title = 'Driver List || Admin Gassin!';
        $navtitle = 'Drivers';
        $drivers = Driver::all();

        foreach ($drivers as $driver) {
            $docs = DriverDocument::where('driver_id', $driver->id)->get();

            if ($docs->count() < 3) {
                $driver->update(['verification_status' => 'pending']);
                continue;
            }

            if ($docs->where('status', 'rejected')->count() > 0) {
                $driver->update(['verification_status' => 'rejected']);
                continue;
            }

            if ($docs->where('status', 'approved')->count() === 3) {
                $driver->update(['verification_status' => 'approved']);
                continue;
            }

            $driver->update(['verification_status' => 'pending']);
        }
        $drivers = Driver::with('user')
            ->withCount('documents')
            ->latest()
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'data' => $drivers->items(),
                'links' => $drivers->linkCollection()
            ]);
        }
        return view('pages.drivers.index', compact('title', 'navtitle', 'drivers'));
    }
}
