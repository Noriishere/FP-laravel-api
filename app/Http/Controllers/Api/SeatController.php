<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RouteStop;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function availability(
        Request $request,
        $id
    ) {

        $request->validate([
            'pickup_stop_id' => 'required|exists:route_stops,id',
            'dropoff_stop_id' => 'required|exists:route_stops,id',
        ]);

        $schedule = Schedule::with([
            'seats',
        ])->findOrFail($id);

        $pickupStop = RouteStop::findOrFail(
            $request->pickup_stop_id
        );

        $dropoffStop = RouteStop::findOrFail(
            $request->dropoff_stop_id
        );

        if (
            $pickupStop->order >= $dropoffStop->order
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid route segment',
            ], 422);
        }

        $bookings = Booking::with([
            'pickupStop',
            'dropoffStop',
            'bookingSeats',
        ])
            ->where(
                'schedule_id',
                $schedule->id
            )
            ->whereIn('payment_status', [
                'paid',
                'pending',
            ])
            ->get();

        $unavailableSeatIds = [];

        foreach ($bookings as $booking) {

            if (
                ! $booking->pickupStop
                ||
                ! $booking->dropoffStop
            ) {
                continue;
            }

            $existingPickup =
                $booking->pickupStop->order;

            $existingDropoff =
                $booking->dropoffStop->order;

            $isOverlap =
                $pickupStop->order
                <
                $existingDropoff
                &&
                $dropoffStop->order
                >
                $existingPickup;

            if ($isOverlap) {

                foreach (
                    $booking->bookingSeats as $bookingSeat
                ) {

                    $unavailableSeatIds[] =
                        $bookingSeat->seat_id;
                }
            }
        }

        $seats = $schedule->seats
            ->map(function ($seat) use (
                $unavailableSeatIds
            ) {

                return [

                    'id' => $seat->id,

                    'seat_number' => $seat->seat_number,

                    'status' => in_array(
                        $seat->id,
                        $unavailableSeatIds
                    )
                            ? 'booked'
                            : 'available',
                ];
            });

        return response()->json([

            'success' => true,

            'data' => [

                'schedule_id' => $schedule->id,

                'pickup_stop' => [
                    'id' => $pickupStop->id,
                    'name' => $pickupStop->name,
                ],

                'dropoff_stop' => [
                    'id' => $dropoffStop->id,
                    'name' => $dropoffStop->name,
                ],

                'total_seats' => $schedule->seats->count(),

                'available_seats' => $seats
                    ->where(
                        'status',
                        'available'
                    )
                    ->count(),

                'booked_seats' => $seats
                    ->where(
                        'status',
                        'booked'
                    )
                    ->count(),

                'seats' => $seats,
            ],
        ]);
    }

    public function checkSeat(
        Request $request,
        $scheduleId
    ) {

        $request->validate([
            'seat_id' => 'required|exists:seats,id',
            'pickup_stop_id' => 'required|exists:route_stops,id',
            'dropoff_stop_id' => 'required|exists:route_stops,id',
        ]);

        $pickup = RouteStop::findOrFail(
            $request->pickup_stop_id
        );

        $dropoff = RouteStop::findOrFail(
            $request->dropoff_stop_id
        );

        $bookings = Booking::with([
            'pickupStop',
            'dropoffStop',
            'bookingSeats',
        ])
            ->where(
                'schedule_id',
                $scheduleId
            )
            ->whereIn('payment_status', [
                'paid',
                'pending',
            ])
            ->get();

        foreach ($bookings as $booking) {

            $hasSeat = $booking->bookingSeats
                ->contains(
                    'seat_id',
                    $request->seat_id
                );

            if (! $hasSeat) {
                continue;
            }

            $existingPickup =
                $booking->pickupStop?->order;

            $existingDropoff =
                $booking->dropoffStop?->order;

            $overlap =
                $pickup->order
                <
                $existingDropoff
                &&
                $dropoff->order
                >
                $existingPickup;

            if ($overlap) {

                return response()->json([
                    'success' => false,
                    'available' => false,
                    'message' => 'Seat already booked on this segment',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'available' => true,
            'message' => 'Seat available',
        ]);
    }
}
