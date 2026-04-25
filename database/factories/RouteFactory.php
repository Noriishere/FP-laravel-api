<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        $cities = [
            ['name' => 'Jakarta', 'lat' => -6.200000, 'lng' => 106.816666],
            ['name' => 'Bandung', 'lat' => -6.914744, 'lng' => 107.609810],
            ['name' => 'Surabaya', 'lat' => -7.257472, 'lng' => 112.752090],
            ['name' => 'Yogyakarta', 'lat' => -7.795580, 'lng' => 110.369490],
            ['name' => 'Semarang', 'lat' => -6.966667, 'lng' => 110.416664],
        ];

        $origin = $this->faker->randomElement($cities);
        $destination = $this->faker->randomElement($cities);

        while ($destination['name'] === $origin['name']) {
            $destination = $this->faker->randomElement($cities);
        }

        $polyline = [
            [$origin['lat'], $origin['lng']],
            [
                ($origin['lat'] + $destination['lat']) / 2,
                ($origin['lng'] + $destination['lng']) / 2
            ],
            [$destination['lat'], $destination['lng']]
        ];

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
