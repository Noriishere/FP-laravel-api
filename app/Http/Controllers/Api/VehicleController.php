<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        $vehicles = Vehicle::latest()->get()->map(function ($v) {
            return [
                'id' => $v->id,
                'name' => $v->name,
                'plate_number' => $v->plate_number,
                'capacity' => $v->capacity,
                'created_at' => $v->created_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    // 🔥 GET BY ID
    public function show($id)
    {
        $v = Vehicle::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $v->id,
                'name' => $v->name,
                'plate_number' => $v->plate_number,
                'capacity' => $v->capacity,
                'created_at' => $v->created_at->toISOString(),
                'updated_at' => $v->updated_at->toISOString(),
            ]
        ]);
    }
}
