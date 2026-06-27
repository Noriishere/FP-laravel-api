<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $origin = strtolower(trim($request->origin ?? ''));
        $destination = strtolower(trim($request->destination ?? ''));

        // 1. Inisialisasi query dasar beserta Eager Loading yang diperlukan saja
        $query = Schedule::with([
            'route.stops' => function ($q) {
                $q->orderBy('order', 'asc'); // Pastikan urutan stop konsisten dari database
            },
            'route.origin',
            'route.destination',
            'vehicle',
            'driver.user',
        ]);

        // 2. Jika user mencari BERDASARKAN ORIGIN DAN DESTINATION sekaligus
        if ($request->origin && $request->destination) {
            $query->whereHas('route', function ($routeQuery) use ($origin, $destination) {
                // Pastikan rute memiliki stop origin (pickup)
                $routeQuery->whereHas('stops', function ($q) use ($origin) {
                    $q->where('name', 'like', "%{$origin}%")->where('is_pickup', true);
                });

                // Pastikan rute memiliki stop destination (dropoff)
                $routeQuery->whereHas('stops', function ($q) use ($destination) {
                    $q->where('name', 'like', "%{$destination}%")->where('is_dropoff', true);
                });
            });

            /* PENTING: Logika urutan rute (origin.order < destination.order)
              sekarang dipindah ke Query Database agar PHP tidak jebol memproses ribuan data.
            */
            $query->whereExists(function ($sqlQuery) use ($origin, $destination) {
                $sqlQuery->select(DB::raw(1))
                    ->from('stops as s1')
                    ->join('stops as s2', 's1.route_id', '=', 's2.route_id')
                    ->whereRaw('s1.route_id = schedules.route_id')
                    ->where('s1.name', 'like', "%{$origin}%")
                    ->where('s1.is_pickup', true)
                    ->where('s2.name', 'like', "%{$destination}%")
                    ->where('s2.is_dropoff', true)
                    ->whereRaw('s1.order < s2.order');
            });

            // 3. Jika hanya mencari Origin saja
        } elseif ($request->origin) {
            $query->whereHas('route.stops', function ($q) use ($origin) {
                $q->where('name', 'like', "%{$origin}%")->where('is_pickup', true);
            });

            // 4. Jika hanya mencari Destination saja
        } elseif ($request->destination) {
            $query->whereHas('route.stops', function ($q) use ($destination) {
                $q->where('name', 'like', "%{$destination}%")->where('is_dropoff', true);
            });
        }

        // 5. Batasi jumlah data (Pagination/Limit) LANGSUNG DI DATABASE sebelum memanggil ->get()
        $schedules = $query
            ->latest()
            ->limit(20) // Ini memastikan maksimal hanya 20 data yang diubah jadi Object PHP
            ->get();

        // 6. Transformasi data untuk Response API
        $data = $schedules->map(function ($schedule) {
            $stops = $schedule->route?->stops
                ->reject(function ($stop) use ($schedule) {
                    return $stop->id === $schedule->route?->origin?->id ||
                           $stop->id === $schedule->route?->destination?->id;
                })
                ->groupBy(function ($stop) {
                    return preg_replace('/\s+/', ' ', strtolower(trim($stop->name)));
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
                        'id' => $schedule->route?->origin?->id,
                        'name' => $schedule->route?->origin?->name,
                    ],
                    'destination' => [
                        'id' => $schedule->route?->destination?->id,
                        'name' => $schedule->route?->destination?->name,
                    ],
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
            'route' => function ($q) {
                $q->select('id', 'name');
            },
            'route.stops' => function ($q) {
                $q->orderBy('order', 'asc');
            },
            'vehicle:id,name,plate_number',
            'driver:id,user_id',
            'driver.user:id,name',
            'seats:id,schedule_id,seat_number',

            'bookings' => function ($q) {
                $q->whereIn('payment_status', ['paid', 'pending'])
                    ->select('id', 'schedule_id', 'pickup_stop_id', 'dropoff_stop_id');
            },
            'bookings.pickupStop:id,order',
            'bookings.dropoffStop:id,order',
            'bookings.bookingSeats:id,booking_id,seat_id',
        ]);

        if ($request->origin) {
            $query->whereHas('route.stops', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->origin.'%')->where('is_pickup', true);
            });
        }

        if ($request->destination) {
            $query->whereHas('route.stops', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->destination.'%')->where('is_dropoff', true);
            });
        }

        if ($request->origin_date) {
            $date = Carbon::parse($request->origin_date);
            $isToday = $date->isToday();

            $start = $isToday
                ? now()->addHours(2)          // kalau hari ini, minimal 2 jam dari sekarang
                : $date->startOfDay();         // kalau hari lain, dari awal hari

            $end = Carbon::parse($request->origin_date)->endOfDay();

            $query->whereBetween('departure_time', [$start, $end]);
        }

        if ($request->destination_date) {
            $start = Carbon::parse($request->destination_date)->startOfDay();
            $end = Carbon::parse($request->destination_date)->endOfDay();
            $query->whereBetween('arrival_time', [$start, $end]);
        }

        if ($request->from_date && $request->to_date) {
            $start = Carbon::parse($request->from_date)->startOfDay();
            $end = Carbon::parse($request->to_date)->endOfDay();
            $query->whereBetween('departure_time', [$start, $end]);
        }

        $dirInput = $request->input('direction', 'asc');
        $direction = is_string($dirInput) ? strtolower($dirInput) : 'asc';

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $schedules = $query->orderBy('departure_time', $direction)
            ->get()
            ->map(function ($schedule) {

                $stops = $schedule->route?->stops?->values();
                $segmentAvailability = [];
                $totalSeats = $schedule->seats->count();

                $bookings = $schedule->bookings;

                if (! $stops || $stops->count() < 2) {
                    $schedule->segment_availability = [];
                    unset($schedule->bookings);

                    return $schedule;
                }

                for ($i = 0; $i < $stops->count() - 1; $i++) {
                    $from = $stops[$i];
                    $to = $stops[$i + 1];
                    $usedSeatIds = [];

                    foreach ($bookings as $booking) {
                        if (! $booking->pickupStop || ! $booking->dropoffStop) {
                            continue;
                        }

                        $bookingPickup = $booking->pickupStop->order;
                        $bookingDropoff = $booking->dropoffStop->order;

                        $segmentOverlap = $from->order < $bookingDropoff && $to->order > $bookingPickup;

                        if ($segmentOverlap) {
                            foreach ($booking->bookingSeats as $bookingSeat) {
                                $usedSeatIds[] = $bookingSeat->seat_id;
                            }
                        }
                    }

                    $usedSeatIds = array_unique($usedSeatIds);

                    $segmentSeats = $schedule->seats->map(function ($seat) use ($usedSeatIds) {
                        return [
                            'id' => $seat->id,
                            'seat_number' => $seat->seat_number,
                            'status' => in_array($seat->id, $usedSeatIds) ? 'booked' : 'available',
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
                        'used_seats' => count($usedSeatIds),
                        'available_seats' => $totalSeats - count($usedSeatIds),
                        'seats' => $segmentSeats,
                    ];
                }

                $schedule->segment_availability = $segmentAvailability;

                unset($schedule->bookings);

                return $schedule;
            });

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }
    // public function sortedByDay(Request $request)
    // {
    //     $query = Schedule::with([
    //         'route' => function ($q) {
    //             $q->select('id', 'name');
    //         },
    //         'route.stops' => function ($q) {
    //             $q->orderBy('order', 'asc');
    //         },
    //         'vehicle:id,name,plate_number',
    //         'driver:id,user_id',
    //         'driver.user:id,name',
    //         'seats:id,schedule_id,seat_number',

    //         'bookings' => function ($q) {
    //             $q->whereIn('payment_status', ['paid', 'pending'])
    //                 ->select('id', 'schedule_id', 'pickup_stop_id', 'dropoff_stop_id');
    //         },
    //         'bookings.pickupStop:id,order',
    //         'bookings.dropoffStop:id,order',
    //         'bookings.bookingSeats:id,booking_id,seat_id',
    //     ]);

    //     if ($request->origin) {
    //         $query->whereHas('route.stops', function ($q) use ($request) {
    //             $q->where('name', 'like', '%'.$request->origin.'%')
    //                 ->where('is_pickup', true);
    //         });
    //     }

    //     if ($request->destination) {
    //         $query->whereHas('route.stops', function ($q) use ($request) {
    //             $q->where('name', 'like', '%'.$request->destination.'%')
    //                 ->where('is_dropoff', true);
    //         });
    //     }

    //     if ($request->origin_date) {
    //         $start = Carbon::parse($request->origin_date)->startOfDay();
    //         $end = Carbon::parse($request->origin_date)->endOfDay();
    //         $query->whereBetween('departure_time', [$start, $end]);
    //     }

    //     if ($request->destination_date) {
    //         $start = Carbon::parse($request->destination_date)->startOfDay();
    //         $end = Carbon::parse($request->destination_date)->endOfDay();
    //         $query->whereBetween('arrival_time', [$start, $end]);
    //     }

    //     if ($request->from_date && $request->to_date) {
    //         $start = Carbon::parse($request->from_date)->startOfDay();
    //         $end = Carbon::parse($request->to_date)->endOfDay();
    //         $query->whereBetween('departure_time', [$start, $end]);
    //     }

    //     // Default hanya tampilkan jadwal 2 jam ke depan
    //     if (
    //         ! $request->origin_date &&
    //         ! $request->destination_date &&
    //         ! $request->from_date &&
    //         ! $request->to_date
    //     ) {
    //         $now = Carbon::now();

    //         $query->whereBetween('departure_time', [
    //             $now,
    //             $now->copy()->addHours(2),
    //         ]);
    //     }

    //     $dirInput = $request->input('direction', 'asc');
    //     $direction = is_string($dirInput) ? strtolower($dirInput) : 'asc';

    //     if (! in_array($direction, ['asc', 'desc'])) {
    //         $direction = 'asc';
    //     }

    //     $schedules = $query
    //         ->orderBy('departure_time', $direction)
    //         ->limit(50)
    //         ->get()
    //         ->map(function ($schedule) {

    //             $stops = $schedule->route?->stops?->values();
    //             $segmentAvailability = [];
    //             $totalSeats = $schedule->seats->count();

    //             $bookings = $schedule->bookings;

    //             if (! $stops || $stops->count() < 2) {
    //                 $schedule->segment_availability = [];
    //                 unset($schedule->bookings);

    //                 return $schedule;
    //             }

    //             for ($i = 0; $i < $stops->count() - 1; $i++) {
    //                 $from = $stops[$i];
    //                 $to = $stops[$i + 1];
    //                 $usedSeatIds = [];

    //                 foreach ($bookings as $booking) {
    //                     if (! $booking->pickupStop || ! $booking->dropoffStop) {
    //                         continue;
    //                     }

    //                     $bookingPickup = $booking->pickupStop->order;
    //                     $bookingDropoff = $booking->dropoffStop->order;

    //                     $segmentOverlap =
    //                         $from->order < $bookingDropoff &&
    //                         $to->order > $bookingPickup;

    //                     if ($segmentOverlap) {
    //                         foreach ($booking->bookingSeats as $bookingSeat) {
    //                             $usedSeatIds[] = $bookingSeat->seat_id;
    //                         }
    //                     }
    //                 }

    //                 $usedSeatIds = array_unique($usedSeatIds);

    //                 $segmentSeats = $schedule->seats->map(function ($seat) use ($usedSeatIds) {
    //                     return [
    //                         'id' => $seat->id,
    //                         'seat_number' => $seat->seat_number,
    //                         'status' => in_array($seat->id, $usedSeatIds)
    //                             ? 'booked'
    //                             : 'available',
    //                     ];
    //                 });

    //                 $segmentAvailability[] = [
    //                     'from_stop' => [
    //                         'id' => $from->id,
    //                         'name' => $from->name,
    //                     ],
    //                     'to_stop' => [
    //                         'id' => $to->id,
    //                         'name' => $to->name,
    //                     ],
    //                     'used_seats' => count($usedSeatIds),
    //                     'available_seats' => $totalSeats - count($usedSeatIds),
    //                     'seats' => $segmentSeats,
    //                 ];
    //             }

    //             $schedule->segment_availability = $segmentAvailability;

    //             unset($schedule->bookings);

    //             return $schedule;
    //         });

    //     return response()->json([
    //         'success' => true,
    //         'data' => $schedules,
    //     ]);
    // }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $origin = strtolower(trim($request->origin));
        $destination = strtolower(trim($request->destination));

        // Gunakan Eager Loading yang sudah "Diet" di atas
        $schedules = Schedule::with([
            'route' => function ($q) {
                $q->select('id', 'name', 'origin_id', 'destination_id');
            },
            'route.stops' => function ($q) {
                $q->orderBy('order', 'asc')->select('stops.id', 'route_id', 'name', 'address', 'order');
            },
            'vehicle:id,name,plate_number',
            'driver:id,user_id',
            'driver.user:id,name',
            'seats', // Jika relasi ini berat, batasi juga kolomnya
        ])
            ->whereHas('route', function ($routeQuery) use ($origin, $destination) {
                // Cek nama stop asal dan tujuan di SQL
                $routeQuery->whereHas('stops', function ($q) use ($origin) {
                    $q->where('name', 'like', "%{$origin}%");
                })->whereHas('stops', function ($q) use ($destination) {
                    $q->where('name', 'like', "%{$destination}%");
                });
            })
            ->whereExists(function ($sqlQuery) use ($origin, $destination) {
                // Pastikan urutan stop asal < stop tujuan di SQL
                $sqlQuery->select(DB::raw(1))
                    ->from('stops as s1')
                    ->join('stops as s2', 's1.route_id', '=', 's2.route_id')
                    ->whereRaw('s1.route_id = schedules.route_id')
                    ->where('s1.name', 'like', "%{$origin}%")
                    ->where('s2.name', 'like', "%{$destination}%")
                    ->whereRaw('s1.order < s2.order');
            })
            ->limit(20) // Wajib ada batas limit jika data sudah ribuan
            ->get();

        return response()->json([
            'success' => true,
            'count' => $schedules->count(),
            'data' => $schedules,
        ]);
    }
}
