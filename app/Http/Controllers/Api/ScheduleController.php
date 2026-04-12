<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
{
    $query = Schedule::with(['route', 'vehicle', 'driver']);

    if ($request->origin) {
        $query->whereHas('route', fn($q) => $q->where('origin', $request->origin));
    }

    if ($request->destination) {
        $query->whereHas('route', fn($q) => $q->where('destination', $request->destination));
    }

    return response()->json($query->get());
}

    public function show($id)
    {
        $schedule = Schedule::with(['route', 'vehicle', 'driver', 'seats'])->findOrFail($id);

        return response()->json($schedule);
    }
}
