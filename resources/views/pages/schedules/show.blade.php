@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Schedule Detail
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Detail perjalanan dan booking segment kursi
            </p>

        </div>

        <a href="{{ route('schedules.index') }}"
            class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl text-sm transition">
            Kembali
        </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-xs uppercase text-gray-400 mb-1">
                Route
            </p>

            <h3 class="font-semibold text-lg">
                {{ $schedule->route->origin?->name }}
                →
                {{ $schedule->route->destination->name }}
            </h3>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-xs uppercase text-gray-400 mb-1">
                Driver
            </p>

            <h3 class="font-semibold text-lg">
                {{ $schedule->driver?->user?->name }}
            </h3>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-xs uppercase text-gray-400 mb-1">
                Vehicle
            </p>

            <h3 class="font-semibold text-lg">
                {{ $schedule->vehicle?->name }}
            </h3>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-xs uppercase text-gray-400 mb-1">
                Departure
            </p>

            <h3 class="font-semibold text-lg">
                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y H:i') }}
            </h3>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-xl font-bold">
                    Route Stops
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Titik pemberhentian perjalanan
                </p>

            </div>

        </div>

        <div class="flex flex-wrap items-center gap-3">

            @foreach($schedule->route->stops as $index => $stop)

                <div class="flex items-center gap-3">

                    <div class="px-4 py-2 rounded-xl
                        @if($index === 0)
                            bg-green-100 text-green-700
                        @elseif($index === $schedule->route->stops->count() - 1)
                            bg-red-100 text-red-700
                        @else
                            bg-blue-100 text-blue-700
                        @endif
                    ">
                        {{ $stop->name }}
                    </div>

                    @if(!$loop->last)

                        <i class="fa-solid fa-arrow-right text-gray-400"></i>

                    @endif

                </div>

            @endforeach

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow p-6">

        <div class="mb-6">

            <h2 class="text-xl font-bold">
                Booking Segments
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Detail kursi berdasarkan segment perjalanan
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b text-left text-gray-500">

                        <th class="pb-3">
                            Customer
                        </th>

                        <th class="pb-3">
                            Seat
                        </th>

                        <th class="pb-3">
                            Pickup
                        </th>

                        <th class="pb-3">
                            Dropoff
                        </th>

                        <th class="pb-3">
                            Segment
                        </th>

                        <th class="pb-3">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($activeBookings as $booking)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="py-4">

                                <div>

                                    <h4 class="font-medium">
                                        {{ $booking->user?->name }}
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $booking->order_id }}
                                    </p>

                                </div>

                            </td>

                            <td class="py-4">

                                <div class="flex flex-wrap gap-2">

                                    @foreach($booking->bookingSeats as $bookingSeat)

                                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-xs font-medium">
                                            Seat {{ $bookingSeat->seat->seat_number }}
                                        </span>

                                    @endforeach

                                </div>

                            </td>

                            <td class="py-4">

                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-medium">
                                    {{ $booking->pickupStop?->name }}
                                </span>

                            </td>

                            <td class="py-4">

                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-medium">
                                    {{ $booking->dropoffStop?->name }}
                                </span>

                            </td>

                            <td class="py-4">

                                @php

                                    $segment =
                                        ($booking->dropoffStop?->order ?? 0)
                                        -
                                        ($booking->pickupStop?->order ?? 0);

                                @endphp

                                <span class="text-sm font-semibold">
                                    {{ $segment }} Segment
                                </span>

                            </td>

                            <td class="py-4">

                                @if($booking->payment_status === 'paid')

                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-medium">
                                        Paid
                                    </span>

                                @else

                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-medium">
                                        Pending
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="py-10 text-center text-gray-400">

                                Belum ada booking

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection