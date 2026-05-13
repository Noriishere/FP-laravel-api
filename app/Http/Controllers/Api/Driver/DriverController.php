<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\BookingSeat;
use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json([
                'message' => 'User is not a driver',
            ], 403);
        }

        $existing = Driver::where('user_id', $user->id)->first();

        if ($existing) {
            return response()->json([
                'message' => 'Driver already exists',
            ], 400);
        }

        $driver = Driver::create([
            'user_id' => $user->id,
            'status' => 'offline',
            'verification_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Driver profile created',
            'driver' => $driver,
        ]);
    }

    public function uploadDocument(Request $request, $id)
    {
        $user = Auth::user();

        $driver = Driver::find($id);

        if (! $driver || $driver->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized driver access',
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:ktp,sim,selfie',
            'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existing = DriverDocument::where('driver_id', $id)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Document already uploaded',
            ], 400);
        }

        $filePath = $request->file('file')->store('driver_documents', 'public');

        $doc = DriverDocument::create([
            'driver_id' => $driver->id,
            'type' => $request->type,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Document uploaded',
            'document' => $doc,
        ]);
    }

    public function routeDetail($id)
    {
        $user = auth('api')->user();

        $driver = $user->driver;

        $schedule = Schedule::with([

            'vehicle',

            'route.stops',

            'stopTimes.stop',

            'bookings.user',
            'bookings.pickupStop',
            'bookings.dropoffStop',
            'bookings.bookingSeats.seat',

        ])
            ->where(
                'driver_id',
                $driver->id
            )
            ->findOrFail($id);

        $stops = $schedule->route?->stops
            ?->sortBy('order')
            ?->values();

        $segments = [];

        for (
            $i = 0;
            $i < $stops->count() - 1;
            $i++
        ) {

            $from = $stops[$i];

            $to = $stops[$i + 1];

            $usedSeats = [];

            foreach (
                $schedule->bookings as $booking
            ) {

                if (
                    ! $booking->pickupStop
                    ||
                    ! $booking->dropoffStop
                ) {
                    continue;
                }

                $pickupOrder =
                    $booking->pickupStop->order;

                $dropoffOrder =
                    $booking->dropoffStop->order;

                $overlap =
                    $from->order
                    <
                    $dropoffOrder
                    &&
                    $to->order
                    >
                    $pickupOrder;

                if ($overlap) {

                    foreach (
                        $booking->bookingSeats as $bookingSeat
                    ) {

                        $usedSeats[] =
                            $bookingSeat->seat?->seat_number;
                    }
                }
            }

            $segments[] = [

                'from_stop' => [
                    'id' => $from->id,
                    'name' => $from->name,
                ],

                'to_stop' => [
                    'id' => $to->id,
                    'name' => $to->name,
                ],

                'used_seats' => array_values(
                    array_unique($usedSeats)
                ),
            ];
        }

        return response()->json([

            'success' => true,

            'data' => [

                'schedule' => [
                    'id' => $schedule->id,
                    'departure_time' => $schedule->departure_time,
                    'arrival_time' => $schedule->arrival_time,
                    'status' => $schedule->status,
                ],

                'vehicle' => [
                    'id' => $schedule->vehicle?->id,
                    'name' => $schedule->vehicle?->name,
                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'route' => [
                    'id' => $schedule->route?->id,
                    'name' => $schedule->route?->name,
                    'polyline' => json_decode(
                        $schedule->route?->polyline
                    ),

                    'stops' => $stops->map(function ($stop) {

                        return [
                            'id' => $stop->id,
                            'name' => $stop->name,
                            'address' => $stop->address,
                            'lat' => $stop->lat,
                            'lng' => $stop->lng,
                            'order' => $stop->order,
                        ];
                    }),
                ],

                'segments' => $segments,

                'bookings' => $schedule->bookings
                    ->map(function ($booking) {

                        return [

                            'id' => $booking->id,

                            'customer' => [
                                'name' => $booking->user?->name,
                            ],

                            'pickup_stop' => $booking->pickupStop?->name,

                            'dropoff_stop' => $booking->dropoffStop?->name,

                            'seats' => $booking->bookingSeats
                                ->map(function ($seat) {

                                    return $seat->seat?->seat_number;
                                }),
                        ];
                    }),
            ],
        ]);
    }

    public function mySchedules()
    {
        $user = auth('api')->user();

        $driver = $user->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        $schedules = Schedule::with([

            'vehicle',

            'route.stops',

            'bookings',

        ])
            ->where(
                'driver_id',
                $driver->id
            )
            ->latest()
            ->get()
            ->map(function ($schedule) {

                $totalSeats =
                    $schedule->seats()
                        ->count();

                $bookedSeats =
                    BookingSeat::whereHas(
                        'booking',
                        function ($q) use ($schedule) {

                            $q->where(
                                'schedule_id',
                                $schedule->id
                            )->whereIn(
                                'payment_status',
                                [
                                    'paid',
                                    'pending',
                                ]
                            );
                        }
                    )->count();

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

                    'route' => [

                        'id' => $schedule->route?->id,

                        'name' => $schedule->route?->name,

                        'total_stops' => $schedule->route?->stops
                            ?->count(),

                        'stops' => $schedule->route?->stops
                            ?->sortBy('order')
                            ?->values()
                            ?->map(function ($stop) {

                                return [
                                    'id' => $stop->id,
                                    'name' => $stop->name,
                                    'order' => $stop->order,
                                ];
                            }),
                    ],

                    'booking_count' => $schedule->bookings
                        ->count(),

                    'seat_summary' => [

                    'total_seats' => $totalSeats,

                    'booked_seats' => $bookedSeats,

                    'available_seats' => $totalSeats
                            -
                            $bookedSeats,
                ],
                ];
            });

        return response()->json([

            'success' => true,

            'data' => $schedules,
        ]);
    }
}
