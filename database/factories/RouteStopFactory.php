<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RouteStopFactory extends Factory
{
    public function definition(): array
    {
        return [

            'code' => strtoupper(
                fake()->unique()->bothify('STP###')
            ),

            'name' => fake()->city(),

            'address' => fake()->address(),

            'lat' => fake()->latitude(),

            'lng' => fake()->longitude(),

            'order' => 1,

            'is_pickup' => true,

            'is_dropoff' => true,
        ];
    }
}