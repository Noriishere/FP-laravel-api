<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        $cities = [
            [
                'name' => 'Jakarta',
                'address' => 'Jakarta, Indonesia',
                'lat' => -6.200000,
                'lng' => 106.816666,
            ],
            [
                'name' => 'Bandung',
                'address' => 'Bandung, Jawa Barat, Indonesia',
                'lat' => -6.914744,
                'lng' => 107.609810,
            ],
            [
                'name' => 'Semarang',
                'address' => 'Semarang, Jawa Tengah, Indonesia',
                'lat' => -6.966667,
                'lng' => 110.416664,
            ],
            [
                'name' => 'Surabaya',
                'address' => 'Surabaya, Jawa Timur, Indonesia',
                'lat' => -7.257472,
                'lng' => 112.752090,
            ],
            [
                'name' => 'Cirebon',
                'address' => 'Cirebon, Jawa Barat, Indonesia',
                'lat' => -6.732023,
                'lng' => 108.552315,
            ],
            [
                'name' => 'Bekasi',
                'address' => 'Bekasi, Jawa Barat, Indonesia',
                'lat' => -6.234900,
                'lng' => 106.989601,
            ],
        ];

        $points = collect($cities)
            ->shuffle()
            ->take(rand(3, 5))
            ->values()
            ->toArray();

        $routeName =
            $points[0]['name'] .
            ' - ' .
            $points[count($points) - 1]['name'];

        $polyline = $this->generatePolyline($points);

        return [
            'name' => $routeName,
            'distance' => rand(100, 800),
            'polyline' => json_encode($polyline),
            'is_active' => true,
        ];
    }

    public function generateStops(): array
    {
        $cities = [
            [
                'name' => 'Jakarta',
                'address' => 'Jakarta, Indonesia',
                'lat' => -6.200000,
                'lng' => 106.816666,
            ],
            [
                'name' => 'Bandung',
                'address' => 'Bandung, Jawa Barat, Indonesia',
                'lat' => -6.914744,
                'lng' => 107.609810,
            ],
            [
                'name' => 'Semarang',
                'address' => 'Semarang, Jawa Tengah, Indonesia',
                'lat' => -6.966667,
                'lng' => 110.416664,
            ],
            [
                'name' => 'Surabaya',
                'address' => 'Surabaya, Jawa Timur, Indonesia',
                'lat' => -7.257472,
                'lng' => 112.752090,
            ],
            [
                'name' => 'Cirebon',
                'address' => 'Cirebon, Jawa Barat, Indonesia',
                'lat' => -6.732023,
                'lng' => 108.552315,
            ],
            [
                'name' => 'Bekasi',
                'address' => 'Bekasi, Jawa Barat, Indonesia',
                'lat' => -6.234900,
                'lng' => 106.989601,
            ],
        ];

        $points = collect($cities)
            ->shuffle()
            ->take(rand(3, 5))
            ->values();

        return $points->map(function ($point, $index) {

            return [
                'code' => strtoupper(fake()->unique()->bothify('STP###')),
                'name' => $point['name'],
                'address' => $point['address'],
                'lat' => $point['lat'],
                'lng' => $point['lng'],
                'order' => $index + 1,
                'is_pickup' => true,
                'is_dropoff' => true,
            ];
        })->toArray();
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
                [
                    $points[count($points) - 1]['lat'],
                    $points[count($points) - 1]['lng']
                ]
            ];
        }

        $data = $response->json();

        if (!isset($data['routes'][0]['geometry']['coordinates'])) {

            return [
                [$points[0]['lat'], $points[0]['lng']],
                [
                    $points[count($points) - 1]['lat'],
                    $points[count($points) - 1]['lng']
                ]
            ];
        }

        return collect($data['routes'][0]['geometry']['coordinates'])
            ->map(fn($coord) => [$coord[1], $coord[0]])
            ->toArray();
    }
}