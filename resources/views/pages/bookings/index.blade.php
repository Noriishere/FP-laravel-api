@extends('layouts.app')

@section('content')
    <div class="bg-white p-5 rounded-xl shadow">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Booking</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2">User</th>
                        <th class="pb-2">Route</th>
                        <th class="pb-2">Driver</th>
                        <th class="pb-2">Kendaraan</th>
                        <th class="pb-2">Seat</th>
                        <th class="pb-2">Total</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2">Payment</th>
                        <th class="pb-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bookings as $booking)
                        <tr class="border-b hover:bg-gray-50">

                            {{-- USER --}}
                            <td class="py-3">
                                {{ $booking->user->name ?? '-' }}
                            </td>

                            {{-- ROUTE --}}
                            <td class="py-3 max-w-[300px]">
                                <div class="truncate">
                                    {{ $booking->schedule->route->origin?->name ?? '-' }}
                                    →
                                    {{ $booking->schedule->route->destination?->name ?? '-' }}
                                </div>
                            </td>

                            {{-- DRIVER --}}
                            <td class="py-3">
                                {{ $booking->schedule->driver->user->name ?? '-' }}
                            </td>

                            {{-- VEHICLE --}}
                            <td class="py-3">
                                {{ $booking->schedule->vehicle->name ?? '-' }}
                            </td>

                            {{-- TOTAL SEAT --}}
                            <td class="py-3">
                                {{ $booking->total_seat }}
                            </td>

                            {{-- TOTAL PRICE --}}
                            <td class="py-3">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="py-3">
                                @if ($booking->status == 'paid')
                                    <span class="text-green-600 text-xs">● Paid</span>
                                @elseif($booking->status == 'pending')
                                    <span class="text-yellow-600 text-xs">● Pending</span>
                                @else
                                    <span class="text-red-600 text-xs">● Cancelled</span>
                                @endif
                            </td>

                            {{-- PAYMENT --}}
                            <td class="py-3">
                                @if ($booking->payment_status == 'paid')
                                    <span class="text-green-600 text-xs">● Paid</span>
                                @elseif($booking->payment_status == 'pending')
                                    <span class="text-yellow-600 text-xs">● Pending</span>
                                @else
                                    <span class="text-red-600 text-xs">● Expired</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="py-3">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="text-blue-600 text-xs">
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-400">
                                Tidak ada booking
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection
