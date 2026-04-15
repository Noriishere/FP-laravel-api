<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['online', 'offline', 'busy']),
            'verification_status' => $this->faker->randomElement(['pending', 'approved', 'rejected'])
        ];
    }
}