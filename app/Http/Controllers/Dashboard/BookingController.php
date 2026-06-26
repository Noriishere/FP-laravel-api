<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $navtitle = 'Bookings';
        $title = 'Bookings || Admin Gassin!';

        $search = $request->search;

        $bookings = Booking::with([
            'user',
            'schedule.route.stops',
            'schedule.driver.user',
            'schedule.vehicle',
            'pickupStop',
            'dropoffStop',
            'bookingSeats.seat',
        ])
            ->when($search, function ($q) use ($search) {

                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.bookings.index',
            compact(
                'bookings',
                'navtitle',
                'title',
                'search'
            )
        );
    }

    public function refund(Booking $booking)
    {
        if ($booking->status == 'cancelled') {
            return back()->with('error', 'Booking sudah direfund.');
        }

        $booking->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return back()->with(
            'success',
            'Refund berhasil diproses.'
        );
    }

    public function show($id)
    {
        $navtitle = 'Detail';
        $title = 'Detail Bookings || Admin Gassin!';
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

            'checker',
        ])->findOrFail($id);

        return view(
            'pages.bookings.show',
            compact('booking', 'navtitle', 'title')
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([

            'status' => 'required|in:pending,paid,cancelled,completed',

            'payment_status' => 'required|in:pending,paid,failed,expired,cancelled',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([

            'status' => $request->status,

            'payment_status' => $request->payment_status,
        ]);

        return back()->with(
            'success',
            'Booking updated'
        );
    }
}
