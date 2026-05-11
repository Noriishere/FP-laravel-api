<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RouteStop;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function availability($id)
    {
        $schedule = Schedule::with([
            'vehicle',
            'seats',
        ])->findOrFail($id);

        $seats = $schedule->seats
            ->map(function ($seat) {

                return [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                ];
            });

        return response()->json([

            'success' => true,

            'data' => [

                'schedule_id' => $schedule->id,

                'vehicle' => [
                    'id' => $schedule->vehicle?->id,
                    'name' => $schedule->vehicle?->name,
                    'plate_number' => $schedule->vehicle?->plate_number,
                ],

                'total_seats' => $schedule->seats->count(),

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
