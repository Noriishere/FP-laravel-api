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
        $orderId = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'schedule_id' => $schedule->id,
            'order_id' => $orderId,
            'total_seat' => $totalSeat,
            'total_price' => $totalSeat * $schedule->price,
            'status' => 'pending',
            'payment_method' => null,
            'expired_at' => null,
        ];
    }
}
