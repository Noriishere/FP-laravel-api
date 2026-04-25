<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class BookingSeatFactory extends Factory
{
    protected $model = BookingSeat::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::inRandomOrder()->first();

        if (!$booking) {
            throw new \Exception('No booking data found');
        }

        $usedSeatIds = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.schedule_id', $booking->schedule_id)
            ->pluck('seat_id');

        $seat = Seat::where('schedule_id', $booking->schedule_id)
            ->whereNotIn('id', $usedSeatIds)
            ->inRandomOrder()
            ->first();

        if (!$seat) {
            throw new \Exception('No available seat');
        }

        return [
            'booking_id' => $booking->id,
            'seat_id' => $seat->id,
        ];
    }
}
