<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        $origin = $this->faker->city();
        $destination = $this->faker->city();

        while ($destination === $origin) {
            $destination = $this->faker->city();
        }

        return [
            'origin' => $origin,
            'destination' => $destination,
        ];
    }
}
