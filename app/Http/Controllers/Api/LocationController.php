<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function update(Request $request)
{
    $request->validate([
        'schedule_id' => 'required|exists:schedules,id',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'speed' => 'nullable|numeric',
        'heading' => 'nullable|numeric',
    ]);

    $last = Location::where('schedule_id', $request->schedule_id)
        ->latest('recorded_at')
        ->first();

    // 🔥 prevent spam (min 5 detik)
    if ($last && now()->diffInSeconds($last->recorded_at) < 5) {
        return response()->json([
            'success' => false,
            'message' => 'Too fast update'
        ], 429);
    }

    $location = Location::create([
        'schedule_id' => $request->schedule_id,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'speed' => $request->speed,
        'heading' => $request->heading,
        'recorded_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'data' => $location
    ]);
}

    public function tracking($scheduleId)
    {
        $location = Location::where('schedule_id', $scheduleId)
            ->latest('recorded_at')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }
}
