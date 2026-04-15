<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Route;
use App\Models\Vehicle;
use App\Models\Driver;
use Carbon\Carbon;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'route_id' => Route::factory(),
            'vehicle_id' => Vehicle::factory(),
            'driver_id' => Driver::factory(),
            'departure_time' => Carbon::now()->addHours(rand(1, 48)),
            'price' => $this->faker->numberBetween(20000, 100000),
            'status' => $this->faker->randomElement(['scheduled', 'on-going', 'completed']),
        ];
    }
}
