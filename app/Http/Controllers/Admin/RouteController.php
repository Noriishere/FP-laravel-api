<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function store(Request $request)
    {
        $request->validate([
            'origin_name' => 'required',
            'destination_name' => 'required',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'stops' => 'nullable|string'
        ]);
        $stopsInput = json_decode($request->stops, true) ?? [];

        $points = [];

        // origin
        $points[] = "{$request->origin_lng},{$request->origin_lat}";

        // stops tengah
        foreach ($stopsInput as $stop) {
            $points[] = "{$stop['lng']},{$stop['lat']}";
        }

        // destination
        $points[] = "{$request->destination_lng},{$request->destination_lat}";

        // jadi string OSRM
        $coordinates = implode(';', $points);
        $routeData = $this->generateRoute($coordinates);

        $route = Route::create([
            'origin_name' => $request->origin_name,
            'destination_name' => $request->destination_name,
            'origin_lat' => $request->origin_lat,
            'origin_lng' => $request->origin_lng,
            'destination_lat' => $request->destination_lat,
            'destination_lng' => $request->destination_lng,
            'distance' => $routeData['distance'],
            'polyline' => json_encode($routeData['polyline']),
        ]);
        $stops = [];
        $stopsInput = json_decode($request->stops, true) ?? [];
        // origin
        $stops[] = [
            'name' => $request->origin_name,
            'lat' => $request->origin_lat,
            'lng' => $request->origin_lng,
            'order' => 1
        ];

        // middle stops (dari FE)
        foreach ($stopsInput as $index => $stop) {
            $stops[] = [
                'name' => 'Stop ' . ($index + 1),
                'lat' => $stop['lat'],
                'lng' => $stop['lng'],
                'order' => $index + 2
            ];
        }

        // destination
        $stops[] = [
            'name' => $request->destination_name,
            'lat' => $request->destination_lat,
            'lng' => $request->destination_lng,
            'order' => count($stops) + 1
        ];

        $route->stops()->createMany($stops);

        return redirect()->route('routes.index')
            ->with('success', 'Route berhasil dibuat');
    }

    public function edit($id)
    {
        $route = Route::findOrFail($id);

        return view('pages.routes.edit', compact('route'));
    }

    public function update(Request $request, $id)
    {
        $route = Route::findOrFail($id);

        $request->validate([
            'origin_name' => 'required',
            'destination_name' => 'required',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
        ]);

        $routeData = $this->generateRoute($coordinates);

        $route->update([
            'origin_name' => $request->origin_name,
            'destination_name' => $request->destination_name,
            'origin_lat' => $request->origin_lat,
            'origin_lng' => $request->origin_lng,
            'destination_lat' => $request->destination_lat,
            'destination_lng' => $request->destination_lng,
            'distance' => $routeData['distance'],
            'polyline' => json_encode($routeData['polyline']),
        ]);

        return redirect()->route('routes.index')
            ->with('success', 'Route berhasil diupdate');
    }

    public function destroy($id)
    {
        $route = Route::findOrFail($id);

        $route->delete();

        return redirect()->back()->with('success', 'Route berhasil dihapus');
    }

    // 🔥 HELPER OSRM
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
