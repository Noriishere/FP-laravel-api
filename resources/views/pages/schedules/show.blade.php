@extends('layouts.app')

@section('content')
    <div class="bg-white p-5 rounded-xl shadow space-y-5">

        <h2 class="text-lg font-semibold">Detail Jadwal</h2>

        {{-- ROUTE --}}
        <div class="text-sm text-gray-600">
            <b>Route:</b>
            {{ $schedule->route->origin_name }}
            →
            {{ $schedule->route->destination_name }}
        </div>

        {{-- DRIVER --}}
        <div class="text-sm">
            <b>Driver:</b>
            {{ $schedule->driver->user->name ?? '-' }}
        </div>

        {{-- VEHICLE --}}
        <div class="text-sm">
            <b>Kendaraan:</b>
            {{ $schedule->vehicle->name ?? '-' }}
        </div>

        {{-- TIME --}}
        <div class="text-sm">
            <b>Berangkat:</b>
            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y H:i') }}
        </div>

        {{-- PRICE --}}
        <div class="text-sm">
            <b>Harga:</b>
            Rp {{ number_format($schedule->price, 0, ',', '.') }}
        </div>

        {{-- SEATS --}}
        <div>
            <h3 class="font-semibold mb-3">Seat Availability</h3>

            <div class="grid grid-cols-4 gap-3">
                @for ($i = 1; $i <= $schedule->vehicle->capacity; $i++)
                    @php
                        $isBooked = in_array($i, $bookedSeatNumbers);
                    @endphp

                    <div class="{{ $isBooked ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                        {{ $i }}
                    </div>
                @endfor
                @endfor
            </div>

            <div class="mt-4 flex gap-4 text-xs">
                <span class="text-green-600">● Available</span>
                <span class="text-red-600">● Booked</span>
            </div>
        </div>

    </div>
@endsection
