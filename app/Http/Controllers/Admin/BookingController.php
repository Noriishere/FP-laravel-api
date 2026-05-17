<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        dd(now());
        $bookings = Booking::with([
            'user',

            'schedule.route.stops',
            'schedule.driver.user',
            'schedule.vehicle',

            'pickupStop',
            'dropoffStop',

            'bookingSeats.seat'
        ])
            ->latest()
            ->paginate(10);

        return view(
            'pages.bookings.index',
            compact('bookings')
        );
    }

    public function show($id)
    {
        $booking = Booking::with([

            'user',

            'schedule.route.origin',
            'schedule.route.destination',
            'schedule.route.stops',
            'schedule.driver.user',
            'schedule.vehicle',

            'pickupStop',
            'dropoffStop',

            'bookingSeats.seat',

            'checker'
        ])->findOrFail($id);

        return view(
            'pages.bookings.show',
            compact('booking')
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([

            'status' => 'required|in:pending,paid,cancelled,completed',

            'payment_status' => 'required|in:pending,paid,failed,expired,cancelled'
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([

            'status' => $request->status,

            'payment_status' => $request->payment_status
        ]);

        return back()->with(
            'success',
            'Booking updated'
        );
    }
}
