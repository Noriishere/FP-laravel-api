<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate_number' => strtoupper($this->faker->bothify('B #### ??')),
            'capacity' => $this->faker->randomElement([10, 12, 15, 20]),
            'type' => $type = $this->faker->randomElement(['hiace', 'elf', 'bus']),
            'name' => match ($type) {
                'hiace' => $this->faker->randomElement(['Hiace Premio', 'Hiace Commuter']),
                'elf' => $this->faker->randomElement(['Elf Short', 'Elf Long']),
                'bus' => $this->faker->randomElement(['Medium Bus', 'Big Bus']),
            },
            'color' => $this->faker->randomElement(['Hitam', 'Putih', 'Silver', 'Merah'])
        ];
    }
}
