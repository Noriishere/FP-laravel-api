<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class UserController extends Controller
{
    public function myBookings()
    {
        $user = auth('api')->user();

        $bookings = $user->bookings()
            ->with([

                'schedule.route.origin',
                'schedule.route.destination',

                'schedule.vehicle',

                'pickupStop',
                'dropoffStop',

                'bookingSeats.seat',
            ])
            ->latest()
            ->get()
            ->map(function ($booking) {

                return [

                    'id' => $booking->id,

                    'order_id' => $booking->order_id,

                    'status' => $booking->status,

                    'payment_status' => $booking->payment_status,

                    'total_seat' => $booking->total_seat,

                    'total_price' => $booking->total_price,

                    'expired_at' => $booking->expired_at,

                    'created_at' => $booking->created_at,

                    'schedule' => [

                        'id' => $booking->schedule?->id,

                        'departure_time' => $booking->schedule?->departure_time,

                        'arrival_time' => $booking->schedule?->arrival_time,

                        'vehicle' => [

                            'name' => $booking->schedule?->vehicle?->name,

                            'plate_number' => $booking->schedule?->vehicle?->plate_number,
                        ],

                        'route' => [

                            'id' => $booking->schedule?->route?->id,

                            'name' => $booking->schedule?->route?->name,

                            'origin' => [
                                'name' => $booking->schedule?->route?->origin?->name,
                            ],

                            'destination' => [
                                'name' => $booking->schedule?->route?->destination?->name,
                            ],
                        ],
                    ],

                    'trip' => [

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

                        'segment_distance' => (
                            ($booking->dropoffStop?->order ?? 0)
                            -
                            ($booking->pickupStop?->order ?? 0)
                        ),
                    ],

                    'seats' => $booking->bookingSeats
                        ->map(function ($bookingSeat) {

                            return [

                                'id' => $bookingSeat->seat?->id,

                                'seat_number' => $bookingSeat->seat?->seat_number,
                            ];
                        }),

                    'can_pay' => (
                        $booking->payment_status === 'pending'
                        &&
                        $booking->expired_at
                        &&
                        now()->lessThan($booking->expired_at)
                    ),

                    'is_expired' => (
                        $booking->payment_status === 'pending'
                        &&
                        $booking->expired_at
                        &&
                        now()->greaterThan($booking->expired_at)
                    ),
                ];
            });

        return response()->json([

            'success' => true,

            'data' => $bookings,
        ]);
    }

    public function bookingHistoryDetail($id)
    {
        $booking = Booking::with([

            'user',

            'schedule.route.origin',
            'schedule.route.destination',
            'schedule.route.stops',

            'schedule.vehicle',

            'schedule.driver.user',

            'schedule.stopTimes.stop',

            'pickupStop',
            'dropoffStop',

            'bookingSeats.seat',
        ])
            ->where('user_id', auth('api')->id())
            ->find($id);

        if (! $booking) {

            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }

        $schedule = $booking->schedule;

        $pickupOrder = $booking->pickupStop?->order ?? 0;

        $dropoffOrder = $booking->dropoffStop?->order ?? 0;

        $segmentDistance = $dropoffOrder - $pickupOrder;

        $pickupTime = optional(
            $schedule->stopTimes
                ->firstWhere(
                    'stop_id',
                    $booking->pickup_stop_id
                )
        )->departure_time;

        $dropoffTime = optional(
            $schedule->stopTimes
                ->firstWhere(
                    'stop_id',
                    $booking->dropoff_stop_id
                )
        )->arrival_time;

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $booking->id,

                'order_id' => $booking->order_id,

                'status' => $booking->status,

                'payment_status' => $booking->payment_status,

                'payment_provider' => $booking->payment_provider,

                'total_seat' => $booking->total_seat,

                'total_price' => $booking->total_price,

                'expired_at' => $booking->expired_at,

                'created_at' => $booking->created_at,

                'customer' => [

                    'id' => $booking->user?->id,

                    'name' => $booking->user?->name,

                    'email' => $booking->user?->email,
                ],

                'schedule' => [

                    'id' => $schedule?->id,

                    'status' => $schedule?->status,

                    'departure_time' => $schedule?->departure_time,

                    'arrival_time' => $schedule?->arrival_time,

                    'price' => $schedule?->price,

                    'vehicle' => [

                        'id' => $schedule?->vehicle?->id,

                        'name' => $schedule?->vehicle?->name,

                        'plate_number' => $schedule?->vehicle?->plate_number,

                        'seat_capacity' => $schedule?->vehicle?->seat_capacity,
                    ],

                    'driver' => [

                        'id' => $schedule?->driver?->id,

                        'name' => $schedule?->driver?->user?->name,

                        'email' => $schedule?->driver?->user?->email,
                    ],

                    'route' => [

                        'id' => $schedule?->route?->id,

                        'name' => $schedule?->route?->name,

                        'distance' => $schedule?->route?->distance,

                        'origin' => [

                            'id' => $schedule?->route?->origin?->id,

                            'name' => $schedule?->route?->origin?->name,

                            'lat' => $schedule?->route?->origin?->lat,

                            'lng' => $schedule?->route?->origin?->lng,
                        ],

                        'destination' => [

                            'id' => $schedule?->route?->destination?->id,

                            'name' => $schedule?->route?->destination?->name,

                            'lat' => $schedule?->route?->destination?->lat,

                            'lng' => $schedule?->route?->destination?->lng,
                        ],

                        'polyline' => json_decode(
                            $schedule?->route?->polyline
                        ),

                        'stops' => $schedule?->route?->stops
                            ?->sortBy('order')
                            ->values()
                            ->map(function ($stop) {

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

                        'stop_times' => $schedule?->stopTimes
                            ?->sortBy(function ($item) {
                                return $item->stop?->order;
                            })
                            ->values()
                            ->map(function ($stopTime) {

                                return [

                                    'stop_id' => $stopTime->stop_id,

                                    'stop_name' => $stopTime->stop?->name,

                                    'arrival_time' => $stopTime->arrival_time,

                                    'departure_time' => $stopTime->departure_time,

                                    'order' => $stopTime->stop?->order,
                                ];
                            }),
                    ],
                ],

                'trip' => [

                    'pickup_stop' => [

                        'id' => $booking->pickupStop?->id,

                        'name' => $booking->pickupStop?->name,

                        'address' => $booking->pickupStop?->address,

                        'lat' => $booking->pickupStop?->lat,

                        'lng' => $booking->pickupStop?->lng,

                        'order' => $booking->pickupStop?->order,

                        'departure_time' => $pickupTime,
                    ],

                    'dropoff_stop' => [

                        'id' => $booking->dropoffStop?->id,

                        'name' => $booking->dropoffStop?->name,

                        'address' => $booking->dropoffStop?->address,

                        'lat' => $booking->dropoffStop?->lat,

                        'lng' => $booking->dropoffStop?->lng,

                        'order' => $booking->dropoffStop?->order,

                        'arrival_time' => $dropoffTime,
                    ],

                    'segment_distance' => $segmentDistance,
                ],

                'seats' => $booking->bookingSeats
                    ->map(function ($bookingSeat) {

                        return [

                            'id' => $bookingSeat->seat?->id,

                            'seat_number' => $bookingSeat->seat?->seat_number,
                        ];
                    }),

                'payment' => [

                    'status' => $booking->payment_status,

                    'provider' => $booking->payment_provider,

                    'expired_at' => $booking->expired_at,

                    'is_expired' => (
                        $booking->payment_status === 'pending'
                        &&
                        $booking->expired_at
                        &&
                        now()->greaterThan($booking->expired_at)
                    ),
                ],
            ],
        ]);
    }

    public function myScheduleDetail($id)
    {
        $user = auth('api')->user();

        if ($user->role !== 'driver') {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $driver = $user->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        $schedule = $driver->schedules()
            ->with([

                'route.origin',
                'route.destination',
                'route.stops',

                'vehicle',

                'bookings.user',
                'bookings.pickupStop',
                'bookings.dropoffStop',
                'bookings.bookingSeats.seat',

                'stopTimes.stop',

                'seats',
            ])
            ->find($id);

        if (! $schedule) {

            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
            ], 404);
        }

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

        $totalPassenger = $activeBookings->sum('total_seat');

        $totalRevenue = $activeBookings
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $vehicleCapacity = $schedule->vehicle?->seat_capacity ?? 0;

        $availableSeat = $vehicleCapacity - $totalPassenger;

        $routeStops = $schedule->route?->stops
            ?->sortBy('order')
            ->values();

        $segments = [];

        if ($routeStops && $routeStops->count() > 1) {

            for ($i = 0; $i < $routeStops->count() - 1; $i++) {

                $from = $routeStops[$i];
                $to = $routeStops[$i + 1];

                $occupied = 0;

                foreach ($activeBookings as $booking) {

                    $pickupOrder = $booking->pickupStop?->order ?? 0;
                    $dropoffOrder = $booking->dropoffStop?->order ?? 0;

                    if (
                        $pickupOrder <= $from->order
                        &&
                        $dropoffOrder > $from->order
                    ) {
                        $occupied += $booking->total_seat;
                    }
                }

                $segments[] = [
                    'from_stop' => $from->name,
                    'to_stop' => $to->name,
                    'occupied_seat' => $occupied,
                    'available_seat' => $vehicleCapacity - $occupied,
                ];
            }
        }

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $schedule->id,

                'status' => $schedule->status,

                'departure_time' => $schedule->departure_time,

                'arrival_time' => $schedule->arrival_time,

                'price' => $schedule->price,

                'vehicle' => [

                    'id' => $schedule->vehicle?->id,

                    'name' => $schedule->vehicle?->name,

                    'plate_number' => $schedule->vehicle?->plate_number,

                    'seat_capacity' => $vehicleCapacity,
                ],

                'summary' => [

                    'total_booking' => $activeBookings->count(),

                    'total_passenger' => $totalPassenger,

                    'occupied_seat' => $totalPassenger,

                    'available_seat' => $availableSeat,

                    'total_revenue' => $totalRevenue,
                ],

                'route' => [

                    'id' => $schedule->route?->id,

                    'name' => $schedule->route?->name,

                    'distance' => $schedule->route?->distance,

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

                    'stops' => $routeStops?->map(function ($stop) {

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
                        ?->sortBy(function ($item) {
                            return $item->stop?->order;
                        })
                        ->values()
                        ->map(function ($stopTime) {

                            return [

                                'stop_id' => $stopTime->stop_id,

                                'stop_name' => $stopTime->stop?->name,

                                'arrival_time' => $stopTime->arrival_time,

                                'departure_time' => $stopTime->departure_time,

                                'order' => $stopTime->stop?->order,
                            ];
                        }),

                    'segments' => $segments,
                ],

                'bookings' => $activeBookings
                    ->map(function ($booking) {

                        return [

                            'id' => $booking->id,

                            'order_id' => $booking->order_id,

                            'payment_status' => $booking->payment_status,

                            'total_seat' => $booking->total_seat,

                            'total_price' => $booking->total_price,

                            'expired_at' => $booking->expired_at,

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

                            'segment' => (
                                ($booking->dropoffStop?->order ?? 0)
                                -
                                ($booking->pickupStop?->order ?? 0)
                            ),

                            'seats' => $booking->bookingSeats
                                ->map(function ($bookingSeat) {

                                    return [

                                        'id' => $bookingSeat->seat?->id,

                                        'seat_number' => $bookingSeat->seat?->seat_number,
                                    ];
                                }),
                        ];
                    }),
            ],
        ]);
    }

    public function mySchedules()
    {
        $user = auth('api')->user();

        if ($user->role !== 'driver') {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $driver = $user->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        $schedules = $driver->schedules()
            ->with([

                'route.origin',
                'route.destination',
                'route.stops',

                'vehicle',

                'bookings.user',
                'bookings.pickupStop',
                'bookings.dropoffStop',
                'bookings.bookingSeats.seat',

                'stopTimes.stop',
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

                                'segment' => ($booking->dropoffStop?->order ?? 0)
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
            'data' => $schedules,
        ]);
    }
}
