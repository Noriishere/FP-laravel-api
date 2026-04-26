<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $title = 'Vehicles || Admin Gassin!';
        $navtitle = 'Vehicles';

        $vehicles = Vehicle::latest()->paginate(10);

        return view('pages.vehicles.index', compact('title', 'navtitle', 'vehicles'));
    }

    public function create()
    {
        $title = 'Create Vehicle';
        return view('pages.vehicles.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'plate_prefix' => 'required',
            'plate_number_main' => 'required',
            'plate_suffix' => 'required',
            'capacity' => 'required|integer|min:1'
        ]);

        $plate = strtoupper(
            $request->plate_prefix . ' ' .
                $request->plate_number_main . ' ' .
                $request->plate_suffix
        );

        Vehicle::create([
            'name' => $request->name,
            'plate_number' => $plate,
            'capacity' => $request->capacity
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created');
    }

    public function edit($id)
    {
        $title = 'Edit Vehicle';
        $vehicle = Vehicle::findOrFail($id);

        // 🔥 pecah plat
        $parts = explode(' ', $vehicle->plate_number);

        $vehicle->plate_prefix = $parts[0] ?? '';
        $vehicle->plate_number_main = $parts[1] ?? '';
        $vehicle->plate_suffix = $parts[2] ?? '';

        return view('pages.vehicles.edit', compact('title', 'vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'plate_number' => 'required|unique:vehicles,plate_number,' . $id,
            'capacity' => 'required|integer|min:1'
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated');
    }

    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();

        return back()->with('success', 'Vehicle deleted');
    }
}
