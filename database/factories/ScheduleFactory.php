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
        $departure = Carbon::now()->addHours(rand(1, 48));
        $duration  = rand(60, 300); // 1–5 jam

        return [
            'route_id' => Route::factory(),
            'vehicle_id' => Vehicle::factory(),
            'driver_id' => Driver::factory(),
            'departure_time' => $departure,
            'arrival_time' => $departure->copy()->addMinutes($duration),
            'price' => $this->faker->numberBetween(20000, 100000),
            'status' => $this->faker->randomElement(['scheduled', 'on-going', 'completed']),
        ];
    }
}
