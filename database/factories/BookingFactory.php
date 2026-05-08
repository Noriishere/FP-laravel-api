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
    public function definition(): array
    {
        $schedule = Schedule::with([
            'route.stops'
        ])
            ->inRandomOrder()
            ->first();

        if (
            !$schedule
            || $schedule->route->stops->count() < 2
        ) {

            throw new \Exception(
                'No valid schedule found'
            );
        }

        $stops = $schedule->route->stops
            ->sortBy('order')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Random Segment
        |--------------------------------------------------------------------------
        */

        $pickupIndex = rand(
            0,
            $stops->count() - 2
        );

        $dropoffIndex = rand(
            $pickupIndex + 1,
            $stops->count() - 1
        );

        $pickupStop = $stops[$pickupIndex];

        $dropoffStop = $stops[$dropoffIndex];

        /*
        |--------------------------------------------------------------------------
        | Seat Count
        |--------------------------------------------------------------------------
        */

        $totalSeat = rand(1, 3);

        /*
        |--------------------------------------------------------------------------
        | Segment Pricing
        |--------------------------------------------------------------------------
        */

        $segmentDistance =
            $dropoffStop->order
            - $pickupStop->order;

        $pricePerSegment =
            $schedule->price
            / max(
                1,
                $stops->count() - 1
            );

        $totalPrice =
            $pricePerSegment
            * $segmentDistance
            * $totalSeat;

        return [

            'user_id' => User::inRandomOrder()
                ->first()
                ->id,

            'schedule_id' => $schedule->id,

            'pickup_stop_id' => $pickupStop->id,

            'dropoff_stop_id' => $dropoffStop->id,

            'order_id' =>
                'INV-'
                . now()->timestamp
                . '-'
                . uniqid(),

            'total_seat' => $totalSeat,

            'total_price' => round($totalPrice),

            'status' => fake()->randomElement([
                'pending',
                'paid',
                'completed'
            ]),

            'payment_status' => fake()->randomElement([
                'pending',
                'paid'
            ]),

            'payment_provider' => null,

            'payment_method' => null,

            'payment_ref' => null,

            'expired_at' => now()
                ->addMinutes(15),
        ];
    }
}