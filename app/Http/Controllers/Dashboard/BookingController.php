<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
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
        // Sudah direfund
        if ($booking->status === 'cancelled') {
            return back()->with(
                'error',
                'Booking sudah direfund.'
            );
        }

        // Harus sudah dibayar
        if ($booking->payment_status !== 'paid') {
            return back()->with(
                'error',
                'Booking belum dibayar.'
            );
        }

        // Maksimal 1 x 24 jam setelah pembayaran
        if (
            $booking->paid_at &&
            Carbon::parse($booking->paid_at)->addDay()->isPast()
        ) {
            return back()->with(
                'error',
                'Refund hanya dapat dilakukan maksimal 1 x 24 jam setelah pembayaran.'
            );
        }

        // Jadwal sudah dimulai
        if (
            Carbon::parse($booking->schedule->departure_time)->isPast()
        ) {
            return back()->with(
                'error',
                'Refund tidak dapat diproses karena perjalanan telah dimulai.'
            );
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
