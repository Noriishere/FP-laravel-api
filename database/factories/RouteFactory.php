<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class RouteFactory extends Factory
{
    private function getPolyline($lat1, $lng1, $lat2, $lng2)
    {
        $url = "https://router.project-osrm.org/route/v1/driving/{$lng1},{$lat1};{$lng2},{$lat2}";

        $response = Http::get($url, [
            'overview' => 'full',
            'geometries' => 'geojson'
        ]);

        if (!$response->successful()) {
            return [
                [$lat1, $lng1],
                [$lat2, $lng2]
            ];
        }

        $data = $response->json();

        if (!isset($data['routes'][0]['geometry']['coordinates'])) {
            return [
                [$lat1, $lng1],
                [$lat2, $lng2]
            ];
        }

        return collect($data['routes'][0]['geometry']['coordinates'])
            ->map(fn($coord) => [$coord[1], $coord[0]])
            ->toArray();
    }

    public function definition(): array
    {
        $cities = [
            ['name' => 'Jakarta', 'lat' => -6.200000, 'lng' => 106.816666],
            ['name' => 'Bandung', 'lat' => -6.914744, 'lng' => 107.609810],
            ['name' => 'Semarang', 'lat' => -6.966667, 'lng' => 110.416664],
            ['name' => 'Surabaya', 'lat' => -7.257472, 'lng' => 112.752090],
        ];

        $origin = fake()->randomElement($cities);
        $destination = fake()->randomElement($cities);

        while ($destination['name'] === $origin['name']) {
            $destination = fake()->randomElement($cities);
        }

        $polyline = $this->getPolyline(
            $origin['lat'],
            $origin['lng'],
            $destination['lat'],
            $destination['lng']
        );

        return [
            'origin_name' => $origin['name'],
            'origin_lat' => $origin['lat'],
            'origin_lng' => $origin['lng'],

            'destination_name' => $destination['name'],
            'destination_lat' => $destination['lat'],
            'destination_lng' => $destination['lng'],

            'distance' => rand(50, 500),
            'polyline' => json_encode($polyline),
        ];
    }
}
