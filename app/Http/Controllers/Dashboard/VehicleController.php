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
            'capacity' => 'required|integer|min:1',
            'type' => 'required',
            'color' => 'required'
        ]);

        $plate = strtoupper(
            $request->plate_prefix . ' ' .
                $request->plate_number_main . ' ' .
                $request->plate_suffix
        );

        Vehicle::create([
            'name' => $request->name,
            'plate_number' => $plate,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'color' => $request->color,
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $title = 'Edit Vehicle';
        $vehicle = Vehicle::findOrFail($id);

        $parts = explode(' ', $vehicle->plate_number);

        $vehicle->plate_prefix = $parts[0] ?? '';
        $vehicle->plate_number_main = $parts[1] ?? '';
        $vehicle->plate_suffix = $parts[2] ?? '';

        return view('pages.vehicles.edit', compact('title', 'vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $plateNumber =
            strtoupper($request->plate_prefix) . ' ' .
            $request->plate_number_main . ' ' .
            strtoupper($request->plate_suffix);

        $request->merge([
            'plate_number' => $plateNumber
        ]);

        $request->validate([
            'name' => 'required',
            'plate_number' => 'required|unique:vehicles,plate_number,' . $id,
            'capacity' => 'required|integer|min:1',
            'type' => 'required',
            'color' => 'required'
        ]);

        $vehicle->update([
            'name' => $request->name,
            'plate_number' => $plateNumber,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'color' => $request->color,
        ]);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil diupdate');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if($vehicle->schedules()->count() > 0) {
            return back()->with('error', 'Kendaraan tidak bisa dihapus karena berkaitan dengan jadwal.');
        }

        $vehicle->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus');
    }
}
