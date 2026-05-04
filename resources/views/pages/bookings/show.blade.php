@extends('layouts.app')

@section('content')
<div class="bg-white p-5 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4">Detail Booking</h2>

    <div class="space-y-2 text-sm">
        <p><b>User:</b> {{ $booking->user->name }}</p>
        <p><b>Order ID:</b> {{ $booking->order_id }}</p>

        <p><b>Route:</b>
            {{ $booking->schedule->route->origin_name }}
            →
            {{ $booking->schedule->route->destination_name }}
        </p>

        <p><b>Driver:</b> {{ $booking->schedule->driver->user->name }}</p>
        <p><b>Kendaraan:</b> {{ $booking->schedule->vehicle->name }}</p>

        <p><b>Total Seat:</b> {{ $booking->total_seat }}</p>
        <p><b>Total Price:</b> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>

        <p><b>Status:</b> {{ $booking->status }}</p>
        <p><b>Payment:</b> {{ $booking->payment_status }}</p>
    </div>

    <div class="mt-5">
        <h3 class="font-semibold mb-2">Seat yang dipilih:</h3>

        <div class="flex gap-2 flex-wrap">
            @foreach ($booking->seats as $seat)
                <span class="px-3 py-1 bg-gray-100 rounded text-xs">
                    {{ $seat->seat_number }}
                </span>
            @endforeach
        </div>
    </div>

</div>
@endsection