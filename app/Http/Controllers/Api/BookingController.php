<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',

            'pickup_stop_id' => 'required|exists:route_stops,id',
            'dropoff_stop_id' => 'required|exists:route_stops,id',

            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer',
        ]);

        $schedule = Schedule::with([
            'route.stops',
        ])->findOrFail($request->schedule_id);

        $pickupStop = $schedule->route->stops
            ->where('id', $request->pickup_stop_id)
            ->first();

        $dropoffStop = $schedule->route->stops
            ->where('id', $request->dropoff_stop_id)
            ->first();

        if (! $pickupStop || ! $dropoffStop) {

            return response()->json([
                'message' => 'Invalid stop selection',
            ], 400);
        }

        if ($pickupStop->order >= $dropoffStop->order) {

            return response()->json([
                'message' => 'Invalid route segment',
            ], 400);
        }

        $seats = Seat::where(
            'schedule_id',
            $request->schedule_id
        )
            ->whereIn('seat_number', $request->seat_ids)
            ->get();

        if ($seats->count() !== count($request->seat_ids)) {

            return response()->json([
                'message' => 'Invalid seat selection',
            ], 400);
        }

        if (
            count($request->seat_ids)
            !== count(array_unique($request->seat_ids))
        ) {

            return response()->json([
                'message' => 'Duplicate seat selection',
            ], 400);
        }

        $seatMap = $seats->pluck(
            'id',
            'seat_number'
        );

        $seatIds = array_values(
            $seatMap->toArray()
        );

        return DB::transaction(function () use (
            $request,
            $schedule,
            $seatIds,
            $pickupStop,
            $dropoffStop
        ) {

            DB::table('seats')
                ->whereIn('id', $seatIds)
                ->lockForUpdate()
                ->get();

            $existingBookings = Booking::with([
                'pickupStop',
                'dropoffStop',
                'bookingSeats',
            ])
                ->where('schedule_id', $request->schedule_id)
                ->where(function ($q) {

                    $q->where('payment_status', 'paid')
                        ->orWhere(function ($q2) {

                            $q2->where(
                                'payment_status',
                                'pending'
                            )
                                ->where(
                                    'expired_at',
                                    '>',
                                    now()
                                );
                        });
                })
                ->get();

            $conflictSeats = [];

            foreach ($existingBookings as $booking) {

                $segmentOverlap =
                    $pickupStop->order
                    < $booking->dropoffStop->order
                    &&
                    $dropoffStop->order
                    > $booking->pickupStop->order;

                if (! $segmentOverlap) {
                    continue;
                }

                $bookedSeatIds = $booking->bookingSeats
                    ->pluck('seat_id')
                    ->toArray();

                $intersection = array_intersect(
                    $seatIds,
                    $bookedSeatIds
                );

                if (! empty($intersection)) {

                    $conflictSeats = array_merge(
                        $conflictSeats,
                        $intersection
                    );
                }
            }

            if (count($conflictSeats) > 0) {

                return response()->json([
                    'message' => 'Seat already booked on this segment',
                    'conflict_seats' => array_values(
                        array_unique($conflictSeats)
                    ),
                ], 409);
            }

            $existing = Booking::where(
                'user_id',
                Auth::id()
            )
                ->where(
                    'schedule_id',
                    $request->schedule_id
                )
                ->where(
                    'payment_status',
                    'pending'
                )
                ->where(
                    'expired_at',
                    '>',
                    now()
                )
                ->exists();

            if ($existing) {

                return response()->json([
                    'message' => 'You still have unpaid booking for this schedule',
                ], 400);
            }

            $segmentDistance =
                $dropoffStop->order
                - $pickupStop->order;

            $pricePerSegment =
                $schedule->price
                / max(
                    1,
                    $schedule->route->stops->count() - 1
                );

            $totalPrice =
                $pricePerSegment
                * $segmentDistance
                * count($seatIds);

            $orderId =
                'INV-'
                .now()->format('YmdHis')
                .'-'
                .uniqid();

            $booking = Booking::create([
                'user_id' => Auth::id(),

                'schedule_id' => $request->schedule_id,

                'pickup_stop_id' => $pickupStop->id,

                'dropoff_stop_id' => $dropoffStop->id,

                'order_id' => $orderId,

                'total_seat' => count($seatIds),

                'total_price' => $totalPrice,

                'status' => 'pending',

                'payment_status' => 'pending',

                'payment_provider' => null,

                'expired_at' => now()->addMinutes(15),
            ]);

            foreach ($seatIds as $seatId) {

                DB::table('booking_seats')->insert([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'schedule_id' => $booking->schedule_id,
                ]);
            }

            return response()->json([
                'message' => 'Booking created, waiting payment',

                'booking_id' => $booking->id,

                'order_id' => $booking->order_id,

                'total_price' => $booking->total_price,

                'segment' => [
                    'pickup' => $pickupStop->name,
                    'dropoff' => $dropoffStop->name,
                ],
            ]);
        });
    }

    public function scan(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'order_id' => 'required|string',
        ]);

        $booking = Booking::with([
            'schedule.route.stops',
            'pickupStop',
            'dropoffStop',
            'user',
            'bookingSeats.seat',
        ])
            ->where('order_id', $request->order_id)
            ->first();

        if (! $booking) {

            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if (
            $booking->payment_status === 'pending'
            &&
            $booking->expired_at < now()
        ) {

            return response()->json([
                'message' => 'Booking expired',
            ], 400);
        }

        if ($booking->payment_status !== 'paid') {

            return response()->json([
                'message' => 'Payment not completed',
            ], 400);
        }

        if ($booking->checked_at) {

            return response()->json([
                'message' => 'Ticket already used',

                'data' => [

                    'user' => $booking->user->name,

                    'route' => $booking->pickupStop?->name
                        .' - '
                        .$booking->dropoffStop?->name,

                    'total_seat' => $booking->total_seat,

                    'seat_numbers' => $booking
                        ->bookingSeats
                        ->pluck('seat.seat_number'),
                ],
            ], 400);
        }

        DB::transaction(function () use ($booking, $user) {

            $booking = Booking::lockForUpdate()
                ->find($booking->id);

            if ($booking->checked_at) {
                throw new \Exception('Ticket already used');
            }

            $booking->update([
                'checked_at' => now(),
                'checked_by' => $user->id,
            ]);
        });

        return response()->json([
            'message' => 'Valid ticket',

            'data' => [

                'user' => $booking->user->name,

                'route' => $booking->pickupStop?->name
                    .' - '
                    .$booking->dropoffStop?->name,

                'total_seat' => $booking->total_seat,

                'seat_numbers' => $booking
                    ->bookingSeats
                    ->pluck('seat.seat_number'),
            ],
        ]);
    }

    public function show($id)
    {
        $booking = Booking::with([

            'user',

            'schedule.route',

            'schedule.vehicle',

            'schedule.driver.user',

            'pickupStop',

            'dropoffStop',

            'bookingSeats.seat',
        ])
            ->where(
                'user_id',
                auth('api')->id()
            )
            ->findOrFail($id);

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $booking->id,

                'order_id' => $booking->order_id,

                'status' => $booking->status,

                'payment_status' => $booking->payment_status,

                'payment_method' => $booking->payment_method,

                'payment_provider' => $booking->payment_provider,

                'total_seat' => $booking->total_seat,

                'total_price' => $booking->total_price,

                'expired_at' => $booking->expired_at,

                'checked_at' => $booking->checked_at,

                'created_at' => $booking->created_at,

                'schedule' => [

                    'id' => $booking->schedule?->id,

                    'departure_time' => $booking->schedule?->departure_time,

                    'arrival_time' => $booking->schedule?->arrival_time,

                    'vehicle' => [

                        'name' => $booking->schedule
                            ?->vehicle
                            ?->name,
                    ],

                    'driver' => [

                        'name' => $booking->schedule
                            ?->driver
                            ?->user
                            ?->name,
                    ],

                    'route' => [

                        'name' => $booking->schedule
                            ?->route
                            ?->name,
                    ],
                ],

                'segment' => [

                    'pickup' => [

                        'id' => $booking->pickupStop?->id,

                        'name' => $booking->pickupStop?->name,
                    ],

                    'dropoff' => [

                        'id' => $booking->dropoffStop?->id,

                        'name' => $booking->dropoffStop?->name,
                    ],
                ],

                'seats' => $booking
                    ->bookingSeats
                    ->map(function ($bookingSeat) {

                        return [

                            'id' => $bookingSeat->seat?->id,

                            'seat_number' => $bookingSeat
                                ->seat
                                ?->seat_number,
                        ];
                    }),
            ],
        ]);
    }
}
