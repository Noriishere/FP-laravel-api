<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        $cities = [
            ['name' => 'Jakarta', 'lat' => -6.200000, 'lng' => 106.816666],
            ['name' => 'Bandung', 'lat' => -6.914744, 'lng' => 107.609810],
            ['name' => 'Semarang', 'lat' => -6.966667, 'lng' => 110.416664],
            ['name' => 'Surabaya', 'lat' => -7.257472, 'lng' => 112.752090],
        ];

        // 🔥 pilih 3-4 titik (origin + stops + destination)
        $points = collect($cities)
            ->shuffle()
            ->take(rand(3, 4))
            ->values()
            ->toArray();

        $origin = $points[0];
        $destination = end($points);

        $polyline = $this->generatePolyline($points);

        return [
            'origin_name' => $origin['name'],
            'origin_lat' => $origin['lat'],
            'origin_lng' => $origin['lng'],

            'destination_name' => $destination['name'],
            'destination_lat' => $destination['lat'],
            'destination_lng' => $destination['lng'],

            'distance' => rand(100, 800),
            'polyline' => json_encode($polyline),
        ];
    }

    private function generatePolyline($points)
    {
        $coordinates = collect($points)
            ->map(fn($p) => "{$p['lng']},{$p['lat']}")
            ->implode(';');

        $url = "https://router.project-osrm.org/route/v1/driving/{$coordinates}";

        $response = Http::get($url, [
            'overview' => 'full',
            'geometries' => 'geojson'
        ]);

        if (!$response->successful()) {
            return [
                [$points[0]['lat'], $points[0]['lng']],
                [$points[count($points)-1]['lat'], $points[count($points)-1]['lng']]
            ];
        }

        $data = $response->json();

        if (!isset($data['routes'][0]['geometry']['coordinates'])) {
            return [
                [$points[0]['lat'], $points[0]['lng']],
                [$points[count($points)-1]['lat'], $points[count($points)-1]['lng']]
            ];
        }

        return collect($data['routes'][0]['geometry']['coordinates'])
            ->map(fn($coord) => [$coord[1], $coord[0]])
            ->toArray();
    }
}