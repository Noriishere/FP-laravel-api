<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::inRandomOrder()->first()->id,
            'seat_number' => $this->faker->numberBetween(1, 20),
            'status' => 'available'
        ];
    }
}
