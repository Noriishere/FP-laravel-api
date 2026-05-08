<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $departure = Carbon::now()
            ->addHours(rand(1, 72));

        $duration = rand(60, 480);

        return [

            'route_id' => Route::factory(),

            'vehicle_id' => Vehicle::factory(),

            'driver_id' => Driver::factory(),

            'departure_time' => $departure,

            'arrival_time' => $departure
                ->copy()
                ->addMinutes($duration),

            'estimated_duration' => $duration,

            'price' => fake()->numberBetween(
                20000,
                150000
            ),

            'status' => fake()->randomElement([
                'completed'
            ]),
        ];
    }
}