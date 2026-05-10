<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function myBookings()
    {
        $user = auth('api')->user();

        $bookings = $user->bookings()
            ->with(['schedule.route', 'bookingSeats.seat'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function mySchedules()
{
    $user = auth('api')->user();

    if ($user->role !== 'driver') {

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $driver = $user->driver;

    if (!$driver) {

        return response()->json([
            'success' => false,
            'message' => 'Driver not found'
        ], 404);
    }

    $schedules = \App\Models\Schedule::where(
        'driver_id',
        $driver->id
    )
        ->with([

            'route.origin',
            'route.destination',
            'route.stops',

            'vehicle',

            'bookings.user',
            'bookings.pickupStop',
            'bookings.dropoffStop',
            'bookings.bookingSeats.seat',

            'stopTimes.stop'
        ])
        ->latest()
        ->get()
        ->map(function ($schedule) {

            $activeBookings = $schedule->bookings
                ->filter(function ($booking) {

                    if ($booking->payment_status === 'paid') {
                        return true;
                    }

                    if (
                        $booking->payment_status === 'pending'
                        &&
                        $booking->expired_at
                        &&
                        $booking->expired_at > now()
                    ) {
                        return true;
                    }

                    return false;
                })
                ->values();

            return [

                'id' => $schedule->id,

                'status' => $schedule->status,

                'departure_time' => $schedule->departure_time,

                'arrival_time' => $schedule->arrival_time,

                'price' => $schedule->price,

                'vehicle' => [
                    'id' => $schedule->vehicle?->id,
                    'name' => $schedule->vehicle?->name,
                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'route' => [

                    'id' => $schedule->route?->id,

                    'name' => $schedule->route?->name,

                    'origin' => [
                        'id' => $schedule->route?->origin?->id,
                        'name' => $schedule->route?->origin?->name,
                        'lat' => $schedule->route?->origin?->lat,
                        'lng' => $schedule->route?->origin?->lng,
                    ],

                    'destination' => [
                        'id' => $schedule->route?->destination?->id,
                        'name' => $schedule->route?->destination?->name,
                        'lat' => $schedule->route?->destination?->lat,
                        'lng' => $schedule->route?->destination?->lng,
                    ],

                    'polyline' => json_decode(
                        $schedule->route?->polyline
                    ),

                    'stops' => $schedule->route?->stops
                        ?->map(function ($stop) {

                            return [
                                'id' => $stop->id,
                                'code' => $stop->code,
                                'name' => $stop->name,
                                'address' => $stop->address,
                                'lat' => $stop->lat,
                                'lng' => $stop->lng,
                                'order' => $stop->order,
                                'is_pickup' => $stop->is_pickup,
                                'is_dropoff' => $stop->is_dropoff,
                            ];
                        }),

                    'stop_times' => $schedule->stopTimes
                        ?->map(function ($stopTime) {

                            return [
                                'stop_id' => $stopTime->stop_id,
                                'stop_name' => $stopTime->stop?->name,
                                'arrival_time' => $stopTime->arrival_time,
                                'departure_time' => $stopTime->departure_time,
                                'order' => $stopTime->stop?->order,
                            ];
                        }),
                ],

                'bookings' => $activeBookings
                    ->map(function ($booking) {

                        return [

                            'id' => $booking->id,

                            'order_id' => $booking->order_id,

                            'customer' => [
                                'id' => $booking->user?->id,
                                'name' => $booking->user?->name,
                                'email' => $booking->user?->email,
                            ],

                            'pickup_stop' => [
                                'id' => $booking->pickupStop?->id,
                                'name' => $booking->pickupStop?->name,
                                'order' => $booking->pickupStop?->order,
                            ],

                            'dropoff_stop' => [
                                'id' => $booking->dropoffStop?->id,
                                'name' => $booking->dropoffStop?->name,
                                'order' => $booking->dropoffStop?->order,
                            ],

                            'segment' =>
                                ($booking->dropoffStop?->order ?? 0)
                                -
                                ($booking->pickupStop?->order ?? 0),

                            'total_seat' => $booking->total_seat,

                            'total_price' => $booking->total_price,

                            'payment_status' => $booking->payment_status,

                            'seats' => $booking->bookingSeats
                                ->map(function ($bookingSeat) {

                                    return [
                                        'id' => $bookingSeat->seat?->id,
                                        'seat_number' => $bookingSeat->seat?->seat_number,
                                    ];
                                }),
                        ];
                    }),
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $schedules
    ]);
}
}
