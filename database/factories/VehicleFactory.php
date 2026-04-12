<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Hiace', 'Elf', 'Travel Bus']),
            'plate_number' => strtoupper($this->faker->bothify('B #### ??')),
            'capacity' => $this->faker->randomElement([10, 12, 15, 20]),
        ];
    }
}