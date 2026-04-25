<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // 🔹 Driver kirim lokasi
    public function update(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

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
            'message' => 'Location updated',
            'data' => $location
        ]);
    }

    // 🔹 Ambil tracking (customer / map)
    public function tracking($scheduleId)
    {
        $locations = Location::where('schedule_id', $scheduleId)
            ->orderBy('recorded_at')
            ->get(['latitude', 'longitude']);

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }
}