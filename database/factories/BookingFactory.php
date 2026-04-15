<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $schedule = Schedule::inRandomOrder()->first();

        if (!$schedule) {
            throw new \Exception('No schedule found');
        }

        $totalSeat = rand(1, 3);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'schedule_id' => $schedule->id,
            'total_seat' => $totalSeat,
            'total_price' => $totalSeat * $schedule->price,
            'status' => $this->faker->randomElement(['pending', 'booked', 'completed']),
        ];
    }
}
