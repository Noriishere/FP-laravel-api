<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $origin = strtolower(
            trim($request->origin ?? '')
        );

        $destination = strtolower(
            trim($request->destination ?? '')
        );

        $query = Schedule::with([
            'route.stops',
            'route.origin',
            'route.destination',
            'vehicle',
            'driver.user',
        ]);

        if (
            $request->origin &&
            $request->destination
        ) {

            $query->whereHas('route', function ($routeQuery) use (
                $origin,
                $destination
            ) {

                $routeQuery
                    ->whereHas('stops', function ($q) use ($origin) {

                        $q->where(
                            'name',
                            'like',
                            "%{$origin}%"
                        )->where(
                            'is_pickup',
                            true
                        );
                    })
                    ->whereHas('stops', function ($q) use ($destination) {

                        $q->where(
                            'name',
                            'like',
                            "%{$destination}%"
                        )->where(
                            'is_dropoff',
                            true
                        );
                    });
            });

        } elseif ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->origin.'%'
                )->where(
                    'is_pickup',
                    true
                );
            });

        } elseif ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->destination.'%'
                )->where(
                    'is_dropoff',
                    true
                );
            });
        }

        $schedules = $query
            ->latest()
            ->get();

        if (
            $request->origin &&
            $request->destination
        ) {

            $schedules = $schedules->filter(function ($schedule) use (
                $origin,
                $destination
            ) {

                $originStop = $schedule->route->stops
                    ->first(function ($stop) use ($origin) {

                        return str_contains(
                            strtolower($stop->name),
                            $origin
                        );
                    });

                $destinationStop = $schedule->route->stops
                    ->first(function ($stop) use ($destination) {

                        return str_contains(
                            strtolower($stop->name),
                            $destination
                        );
                    });

                if (
                    ! $originStop ||
                    ! $destinationStop
                ) {

                    return false;
                }

                return
                    $originStop->order <
                    $destinationStop->order;

            })->values();
        }

        $data = $schedules->map(function ($schedule) {

            $originName = preg_replace(
                '/\s+/',
                ' ',
                strtolower(trim(
                    $schedule->route?->origin?->name
                ))
            );

            $destinationName = preg_replace(
                '/\s+/',
                ' ',
                strtolower(trim(
                    $schedule->route?->destination?->name
                ))
            );

            $stops = $schedule->route?->stops
                ->filter(function ($stop) use (
                    $originName,
                    $destinationName
                ) {

                    $stopName = preg_replace(
                        '/\s+/',
                        ' ',
                        strtolower(trim($stop->name))
                    );

                    return
                        $stopName !== $originName &&
                        $stopName !== $destinationName;
                })
                ->groupBy(function ($stop) {

                    return preg_replace(
                        '/\s+/',
                        ' ',
                        strtolower(trim($stop->name))
                    );
                })
                ->map(function ($group) {

                    return $group->first();
                })
                ->values()
                ->map(function ($stop) {

                    return [

                        'id' => $stop->id,

                        'name' => $stop->name,

                        'address' => $stop->address,

                        'latitude' => $stop->lat,

                        'longitude' => $stop->lng,

                        'order' => $stop->order,

                        'is_pickup' => $stop->is_pickup,

                        'is_dropoff' => $stop->is_dropoff,
                    ];
                });

            return [

                'id' => $schedule->id,

                'departure_time' => $schedule->departure_time,

                'arrival_time' => $schedule->arrival_time,

                'status' => $schedule->status,

                'price' => $schedule->price,

                'vehicle' => [

                    'id' => $schedule->vehicle?->id,

                    'name' => $schedule->vehicle?->name,

                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'driver' => [

                    'id' => $schedule->driver?->id,

                    'name' => $schedule->driver?->user?->name,
                ],

                'route' => [

                    'id' => $schedule->route?->id,

                    'name' => $schedule->route?->name,

                    'origin' => [

                        'name' => $schedule->route?->origin?->name,
                    ],

                    'destination' => [

                        'name' => $schedule->route?->destination?->name,
                    ],

                    'polyline' => json_decode(
                        $schedule->route?->polyline
                    ),

                    'stops' => $stops,
                ],
            ];
        });

        return response()->json([

            'success' => true,

            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user',
            'stopTimes.stop',
        ])->findOrFail($id);
        $stops = $schedule->route->stops
            ->sortBy('order')
            ->values();

        $totalSegments = max(
            1,
            $stops->count() - 1
        );

        $pricePerSegment =
            $schedule->price / $totalSegments;

        $segmentPrices = [];

        for ($i = 0; $i < $stops->count() - 1; $i++) {

            $segmentPrices[] = [

                'from_stop' => [
                    'id' => $stops[$i]->id,
                    'name' => $stops[$i]->name,
                ],

                'to_stop' => [
                    'id' => $stops[$i + 1]->id,
                    'name' => $stops[$i + 1]->name,
                ],

                'price' => $pricePerSegment,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'segment_prices' => $segmentPrices,
        ]);
    }

    public function map($id)
    {
        $schedule = Schedule::with([
            'route.stops',
            'driver.user',
            'stopTimes.stop',
        ])->findOrFail($id);

        $origin = $schedule->route->origin;
        $destination = $schedule->route->destination;

        return response()->json([
            'success' => true,
            'data' => [

                'route' => [

                    'name' => $schedule->route->name,

                    'origin' => [
                        'name' => $origin?->name,
                        'lat' => (float) $origin?->lat,
                        'lng' => (float) $origin?->lng,
                    ],

                    'destination' => [
                        'name' => $destination?->name,
                        'lat' => (float) $destination?->lat,
                        'lng' => (float) $destination?->lng,
                    ],

                    'stops' => $schedule->route->stops,

                    'stop_times' => $schedule->stopTimes,

                    'polyline' => json_decode(
                        $schedule->route->polyline
                    ),
                ],

                'driver' => [
                    'name' => $schedule->driver?->user?->name,
                ],
            ],
        ]);
    }

    public function sorted(Request $request)
    {
        $query = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user',
            'seats',
        ]);

        if ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->origin.'%'
                )->where('is_pickup', true);
            });
        }

        if ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->destination.'%'
                )->where('is_dropoff', true);
            });
        }

        $direction = $request->get(
            'direction',
            'asc'
        );

        if (
            ! in_array(
                $direction,
                ['asc', 'desc']
            )
        ) {

            $direction = 'asc';
        }

        $query->orderBy(
            'departure_time',
            $direction
        );

        $schedules = $query->get()
            ->map(function ($schedule) {

                $stops = $schedule->route?->stops
                    ?->sortBy('order')
                    ?->values();

                $segmentAvailability = [];

                $totalSeats = $schedule->seats
                    ->count();

                $bookings = Booking::with([
                    'pickupStop',
                    'dropoffStop',
                    'bookingSeats',
                ])
                    ->where(
                        'schedule_id',
                        $schedule->id
                    )
                    ->whereIn(
                        'payment_status',
                        [
                            'paid',
                            'pending',
                        ]
                    )
                    ->get();

                if (
                    ! $stops
                    ||
                    $stops->count() < 2
                ) {

                    $schedule->segment_availability = [];

                    return $schedule;
                }

                for (
                    $i = 0;
                    $i < $stops->count() - 1;
                    $i++
                ) {

                    $from = $stops[$i];

                    $to = $stops[$i + 1];

                    $usedSeatIds = [];

                    foreach ($bookings as $booking) {

                        if (
                            ! $booking->pickupStop
                            ||
                            ! $booking->dropoffStop
                        ) {
                            continue;
                        }

                        $bookingPickup =
                            $booking->pickupStop->order;

                        $bookingDropoff =
                            $booking->dropoffStop->order;

                        $segmentOverlap =
                            $from->order
                            <
                            $bookingDropoff
                            &&
                            $to->order
                            >
                            $bookingPickup;

                        if ($segmentOverlap) {

                            foreach (
                                $booking->bookingSeats as $bookingSeat
                            ) {

                                $usedSeatIds[] =
                                    $bookingSeat->seat_id;
                            }
                        }
                    }

                    $usedSeatIds = array_unique(
                        $usedSeatIds
                    );

                    $segmentSeats = $schedule->seats
                        ->map(function ($seat) use (
                            $usedSeatIds
                        ) {

                            return [

                                'id' => $seat->id,

                                'seat_number' => $seat->seat_number,

                                'status' => in_array(
                                    $seat->id,
                                    $usedSeatIds
                                )
                                        ? 'booked'
                                        : 'available',
                            ];
                        });

                    $segmentAvailability[] = [

                        'from_stop' => [
                            'id' => $from->id,
                            'name' => $from->name,
                        ],

                        'to_stop' => [
                            'id' => $to->id,
                            'name' => $to->name,
                        ],

                        'used_seats' => count(
                            $usedSeatIds
                        ),

                        'available_seats' => $totalSeats
                            -
                            count($usedSeatIds),

                        'seats' => $segmentSeats,
                    ];
                }

                $schedule->segment_availability =
                    $segmentAvailability;

                return $schedule;
            });

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function sortedByDay(Request $request)
    {
        $query = Schedule::with([
            'route.stops',
            'vehicle',
            'driver.user',
            'seats',
        ]);

        if ($request->origin) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->origin.'%'
                )->where('is_pickup', true);
            });
        }

        if ($request->destination) {

            $query->whereHas('route.stops', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->destination.'%'
                )->where('is_dropoff', true);
            });
        }

        if ($request->origin_date) {

            $start = Carbon::parse(
                $request->origin_date
            )->startOfDay();

            $end = Carbon::parse(
                $request->origin_date
            )->endOfDay();

            $query->whereBetween(
                'departure_time',
                [$start, $end]
            );
        }

        if ($request->destination_date) {

            $start = Carbon::parse(
                $request->destination_date
            )->startOfDay();

            $end = Carbon::parse(
                $request->destination_date
            )->endOfDay();

            $query->whereBetween(
                'arrival_time',
                [$start, $end]
            );
        }

        if (
            $request->from_date
            &&
            $request->to_date
        ) {

            $start = Carbon::parse(
                $request->from_date
            )->startOfDay();

            $end = Carbon::parse(
                $request->to_date
            )->endOfDay();

            $query->whereBetween(
                'departure_time',
                [$start, $end]
            );
        }

        $direction = $request->get(
            'direction',
            'asc'
        );

        if (
            ! in_array(
                $direction,
                ['asc', 'desc']
            )
        ) {

            $direction = 'asc';
        }

        $query->orderBy(
            'departure_time',
            $direction
        );

        $schedules = $query->get()
            ->map(function ($schedule) {

                $stops = $schedule->route?->stops
                    ?->sortBy('order')
                    ?->values();

                $segmentAvailability = [];

                $totalSeats = $schedule->seats
                    ->count();

                $bookings = Booking::with([
                    'pickupStop',
                    'dropoffStop',
                    'bookingSeats',
                ])
                    ->where(
                        'schedule_id',
                        $schedule->id
                    )
                    ->whereIn(
                        'payment_status',
                        [
                            'paid',
                            'pending',
                        ]
                    )
                    ->get();

                if (
                    ! $stops
                    ||
                    $stops->count() < 2
                ) {

                    $schedule->segment_availability = [];

                    return $schedule;
                }

                for (
                    $i = 0;
                    $i < $stops->count() - 1;
                    $i++
                ) {

                    $from = $stops[$i];

                    $to = $stops[$i + 1];

                    $usedSeatIds = [];

                    foreach ($bookings as $booking) {

                        if (
                            ! $booking->pickupStop
                            ||
                            ! $booking->dropoffStop
                        ) {
                            continue;
                        }

                        $bookingPickup =
                            $booking->pickupStop->order;

                        $bookingDropoff =
                            $booking->dropoffStop->order;

                        $segmentOverlap =
                            $from->order
                            <
                            $bookingDropoff
                            &&
                            $to->order
                            >
                            $bookingPickup;

                        if ($segmentOverlap) {

                            foreach (
                                $booking->bookingSeats as $bookingSeat
                            ) {

                                $usedSeatIds[] =
                                    $bookingSeat->seat_id;
                            }
                        }
                    }

                    $usedSeatIds = array_unique(
                        $usedSeatIds
                    );

                    $segmentSeats = $schedule->seats
                        ->map(function ($seat) use (
                            $usedSeatIds
                        ) {

                            return [

                                'id' => $seat->id,

                                'seat_number' => $seat->seat_number,

                                'status' => in_array(
                                    $seat->id,
                                    $usedSeatIds
                                )
                                        ? 'booked'
                                        : 'available',
                            ];
                        });

                    $segmentAvailability[] = [

                        'from_stop' => [
                            'id' => $from->id,
                            'name' => $from->name,
                        ],

                        'to_stop' => [
                            'id' => $to->id,
                            'name' => $to->name,
                        ],

                        'used_seats' => count(
                            $usedSeatIds
                        ),

                        'available_seats' => $totalSeats
                            -
                            count($usedSeatIds),

                        'seats' => $segmentSeats,
                    ];
                }

                $schedule->segment_availability =
                    $segmentAvailability;

                return $schedule;
            });

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $origin = strtolower(
            trim($request->origin)
        );

        $destination = strtolower(
            trim($request->destination)
        );

        $schedules = Schedule::with([
            'stopTimes.stop',
            'route',
            'vehicle',
            'driver.user',
            'seats',
        ])->get();

        $filtered = $schedules->filter(
            function ($schedule) use ($origin, $destination) {

                $stops = $schedule->stopTimes;

                $originStop = $stops->first(
                    function ($stopTime) use ($origin) {

                        if (! $stopTime->stop) {
                            return false;
                        }

                        return
                            str_contains(
                                strtolower(
                                    $stopTime->stop->name
                                ),
                                $origin
                            )

                            ||

                            str_contains(
                                strtolower(
                                    $stopTime->stop->address
                                ),
                                $origin
                            );
                    }
                );

                $destinationStop = $stops->first(
                    function ($stopTime) use ($destination) {

                        if (! $stopTime->stop) {
                            return false;
                        }

                        return
                            str_contains(
                                strtolower(
                                    $stopTime->stop->name
                                ),
                                $destination
                            )

                            ||

                            str_contains(
                                strtolower(
                                    $stopTime->stop->address
                                ),
                                $destination
                            );
                    }
                );

                if (
                    ! $originStop
                    ||
                    ! $destinationStop
                ) {
                    dd([
                        'origin' => $origin,
                        'destination' => $destination,
                        'stops' => $stops->map(function ($s) {

                            return [
                                'name' => $s->stop?->name,
                                'address' => $s->stop?->address,
                                'order' => $s->stop_order,
                            ];
                        }),
                        'originStop' => $originStop,
                        'destinationStop' => $destinationStop,
                    ]);

                    return false;
                }

                return
                    $originStop->stop_order
                    <
                    $destinationStop->stop_order;
            }
        )->values();

        return response()->json([
            'success' => true,
            'count' => $filtered->count(),
            'data' => $filtered,
        ]);
    }
}
