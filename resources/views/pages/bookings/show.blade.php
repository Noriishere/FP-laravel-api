@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="bg-white p-6 rounded-2xl shadow">

            <div class="flex items-center justify-between mb-6">

                <div>

                    <h2 class="text-2xl font-bold text-gray-800">
                        Detail Booking
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $booking->order_id }}
                    </p>

                </div>

                <span
                    class="px-4 py-2 rounded-xl text-sm font-medium
                @if ($booking->payment_status === 'paid') bg-green-100 text-green-700
                @elseif($booking->payment_status === 'pending')
                    bg-yellow-100 text-yellow-700
                @else
                    bg-red-100 text-red-700 @endif
            ">
                    {{ ucfirst($booking->payment_status) }}
                </span>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Customer
                    </p>

                    <h3 class="text-lg font-semibold">
                        {{ $booking->user->name }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $booking->user->email }}
                    </p>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Route
                    </p>

                    <h3 class="text-lg font-semibold">
                        {{ $booking->schedule->route->origin?->name }}
                        →
                        {{ $booking->schedule->route->destination?->name }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Pickup Stop
                    </p>

                    <div class="inline-flex px-4 py-2 rounded-xl bg-green-100 text-green-700 font-medium">
                        {{ $booking->pickupStop?->name }}
                    </div>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Dropoff Stop
                    </p>

                    <div class="inline-flex px-4 py-2 rounded-xl bg-red-100 text-red-700 font-medium">
                        {{ $booking->dropoffStop?->name }}
                    </div>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Driver
                    </p>

                    <h3 class="text-lg font-semibold">
                        {{ $booking->schedule->driver->user->name ?? '-' }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Vehicle
                    </p>

                    <h3 class="text-lg font-semibold">
                        {{ $booking->schedule->vehicle->name ?? '-' }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="text-xl font-bold mb-6">
                Booking Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Total Seat
                    </p>

                    <h3 class="text-2xl font-bold">
                        {{ $booking->total_seat }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Total Price
                    </p>

                    <h3 class="text-2xl font-bold text-primary">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Segment
                    </p>

                    @php

                        $segment = ($booking->dropoffStop?->order ?? 0) - ($booking->pickupStop?->order ?? 0);
                    @endphp

                    <h3 class="text-2xl font-bold">
                        {{ $segment }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Payment
                    </p>

                    <h3 class="text-lg font-semibold">
                        {{ ucfirst($booking->payment_status) }}
                    </h3>

                </div>
                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Created At
                    </p>

                    <h3 class="text-sm font-semibold">
                        {{ $booking->created_at->format('d M Y H:i') }}
                    </h3>

                </div>

                <div class="border rounded-xl p-5">

                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Expired At
                    </p>

                    <h3 class="text-sm font-semibold">

                        @if ($booking->expired_at)
                            {{ \Carbon\Carbon::parse($booking->expired_at)->format('d M Y H:i') }}
                        @else
                            -
                        @endif

                    </h3>

                </div>
            </div>

        </div>

        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="text-xl font-bold mb-5">
                Seat Information
            </h3>

            <div class="flex flex-wrap gap-3">

                @foreach ($booking->bookingSeats as $bookingSeat)
                    <div class="px-5 py-3 rounded-xl bg-primary/10 text-primary font-semibold">
                        Seat {{ $bookingSeat->seat->seat_number }}
                    </div>
                @endforeach

            </div>

        </div>

        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="text-xl font-bold mb-6">
                Route Timeline
            </h3>

            <div class="space-y-4">

                @foreach ($booking->schedule->route->stops as $index => $stop)
                    <div class="flex items-start gap-4">

                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold
                        @if ($stop->id == $booking->pickup_stop_id) bg-green-500
                        @elseif($stop->id == $booking->dropoff_stop_id)
                            bg-red-500
                        @else
                            bg-blue-500 @endif
                    ">
                            {{ $index + 1 }}
                        </div>

                        <div>

                            <h4 class="font-semibold text-gray-800">
                                {{ $stop->name }}
                            </h4>

                            <div class="flex gap-2 mt-2">

                                @if ($stop->id == $booking->pickup_stop_id)
                                    <span class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                        Pickup
                                    </span>
                                @endif

                                @if ($stop->id == $booking->dropoff_stop_id)
                                    <span class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-full">
                                        Dropoff
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        @if ($booking->checker)
            <div class="bg-white p-6 rounded-2xl shadow">

                <h3 class="text-xl font-bold mb-4">
                    Checked By
                </h3>

                <div class="flex items-center justify-between">

                    <div>

                        <h4 class="font-semibold">
                            {{ $booking->checker->name }}
                        </h4>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $booking->checked_at }}
                        </p>

                    </div>

                    <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-medium">
                        Checked
                    </span>

                </div>

            </div>
        @endif

    </div>
@endsection
