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
            'order_id' => 'INV-' . now()->timestamp . '-' . uniqid(),
            'total_seat' => $totalSeat,
            'total_price' => $totalSeat * $schedule->price,

            'status' => 'pending',
            'payment_status' => 'pending',

            'payment_provider' => null,
            'payment_method' => null,
            'payment_ref' => null,

            'expired_at' => null,
        ];
    }
}
