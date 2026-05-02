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

    public function definition(): array
    {
        return [
            'booking_id' => Booking::inRandomOrder()->first()->id,
            'seat_id' => Seat::inRandomOrder()->first()->id,
        ];
    }
}