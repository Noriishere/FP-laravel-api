<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::latest()->get();

        return view('pages.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('pages.routes.create');
    }
    public function show($id)
    {
        $route = Route::with([
            'stops'
        ])->findOrFail($id);

        return view('pages.routes.show', compact('route'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stops' => 'required|string'
        ]);

        $stopsInput = json_decode(
            $request->stops,
            true
        );

        if (
            !$stopsInput
            || count($stopsInput) < 2
        ) {

            return back()->withErrors([
                'stops' => 'Minimal harus memiliki origin dan destination'
            ]);
        }

        $pickupExists = collect($stopsInput)
            ->contains(fn($stop) => $stop['is_pickup'] ?? false);

        $dropoffExists = collect($stopsInput)
            ->contains(fn($stop) => $stop['is_dropoff'] ?? false);

        if (!$pickupExists || !$dropoffExists) {

            return back()->withErrors([
                'stops' => 'Minimal harus ada pickup dan dropoff stop'
            ]);
        }

        $duplicates = collect($stopsInput)
            ->pluck('name')
            ->duplicates();

        if ($duplicates->count()) {

            return back()->withErrors([
                'stops' => 'Terdapat stop duplicate'
            ]);
        }

        $points = [];

        foreach ($stopsInput as $stop) {

            $points[] =
                "{$stop['lng']},{$stop['lat']}";
        }

        $coordinates = implode(';', $points);

        $routeData = $this->generateRoute(
            $coordinates
        );

        DB::transaction(function () use (
            $request,
            $routeData,
            $stopsInput
        ) {

            $route = Route::create([

                'name' => $request->name,

                'distance' => $routeData['distance'],

                'polyline' => json_encode(
                    $routeData['polyline']
                ),

                'is_active' => true
            ]);

            $stops = [];

            foreach ($stopsInput as $index => $stop) {

                $stops[] = [

                    'code' => strtoupper(
                        Str::random(6)
                    ),

                    'name' => $stop['name'],

                    'address' => $stop['address']
                        ?? null,

                    'lat' => $stop['lat'],

                    'lng' => $stop['lng'],

                    'order' => $index + 1,

                    'is_pickup' =>
                    $stop['is_pickup'] ?? true,

                    'is_dropoff' =>
                    $stop['is_dropoff'] ?? true,

                    'created_at' => now(),

                    'updated_at' => now(),
                ];
            }

            $route->stops()->createMany($stops);
        });

        return redirect()
            ->route('routes.index')
            ->with(
                'success',
                'Route berhasil dibuat'
            );
    }

    public function update(
        Request $request,
        $id
    ) {

        $route = Route::with([
            'stops',
            'schedules.bookings'
        ])->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'stops' => 'required|string'
        ]);

        $stopsInput = json_decode(
            $request->stops,
            true
        );

        if (
            !$stopsInput
            || count($stopsInput) < 2
        ) {

            return back()->withErrors([
                'stops' => 'Minimal harus memiliki origin dan destination'
            ]);
        }

        $pickupExists = collect($stopsInput)
            ->contains(fn($stop) => $stop['is_pickup'] ?? false);

        $dropoffExists = collect($stopsInput)
            ->contains(fn($stop) => $stop['is_dropoff'] ?? false);

        if (!$pickupExists || !$dropoffExists) {

            return back()->withErrors([
                'stops' => 'Minimal harus ada pickup dan dropoff stop'
            ]);
        }

        $duplicates = collect($stopsInput)
            ->pluck('name')
            ->duplicates();

        if ($duplicates->count()) {

            return back()->withErrors([
                'stops' => 'Terdapat stop duplicate'
            ]);
        }

        $hasBookings = $route->schedules
            ->flatMap(fn($schedule) => $schedule->bookings)
            ->count() > 0;

        if ($hasBookings) {

            return back()->withErrors([
                'route' =>
                'Route tidak dapat diubah karena sudah memiliki booking'
            ]);
        }

        $points = [];

        foreach ($stopsInput as $stop) {

            $points[] =
                "{$stop['lng']},{$stop['lat']}";
        }

        $coordinates = implode(';', $points);

        $routeData = $this->generateRoute(
            $coordinates
        );

        DB::transaction(function () use (
            $route,
            $request,
            $routeData,
            $stopsInput
        ) {

            $route->update([

                'name' => $request->name,

                'distance' => $routeData['distance'],

                'polyline' => json_encode(
                    $routeData['polyline']
                ),
            ]);

            $route->stops()->delete();

            $stops = [];

            foreach ($stopsInput as $index => $stop) {

                $stops[] = [

                    'code' => strtoupper(
                        Str::random(6)
                    ),

                    'name' => $stop['name'],

                    'address' => $stop['address']
                        ?? null,

                    'lat' => $stop['lat'],

                    'lng' => $stop['lng'],

                    'order' => $index + 1,

                    'is_pickup' =>
                    $stop['is_pickup'] ?? true,

                    'is_dropoff' =>
                    $stop['is_dropoff'] ?? true,

                    'created_at' => now(),

                    'updated_at' => now(),
                ];
            }

            $route->stops()->createMany($stops);
        });

        return redirect()
            ->route('routes.index')
            ->with(
                'success',
                'Route berhasil diupdate'
            );
    }

    public function edit($id)
    {
        $route = Route::with('stops')->findOrFail($id);

        return view('pages.routes.edit', compact('route'));
    }


    public function destroy($id)
    {
        $route = Route::findOrFail($id);

        $route->delete();

        return redirect()
            ->back()
            ->with('success', 'Route berhasil dihapus');
    }

    private function generateRoute($coordinates)
    {
        $url = "https://router.project-osrm.org/route/v1/driving/{$coordinates}";

        $response = Http::get($url, [
            'overview' => 'full',
            'geometries' => 'geojson'
        ]);

        $data = $response->json();

        if (!isset($data['routes'][0])) {
            return [
                'distance' => 0,
                'polyline' => []
            ];
        }

        $route = $data['routes'][0];

        $polyline = collect($route['geometry']['coordinates'])
            ->map(fn($coord) => [$coord[1], $coord[0]])
            ->toArray();

        return [
            'distance' => round($route['distance'] / 1000, 2),
            'polyline' => $polyline
        ];
    }
}
