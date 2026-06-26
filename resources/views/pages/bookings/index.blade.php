@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Booking</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola seluruh data booking pelanggan GASSIN.</p>
            </div>

            {{-- Search --}}
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user atau Order ID..."
                    class="w-72 rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('bookings.index') }}"
                        class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 text-left">User</th>
                        <th class="px-6 py-4 text-left">Route</th>
                        <th class="px-6 py-4 text-left">Driver</th>
                        <th class="px-6 py-4 text-left">Vehicle</th>
                        <th class="px-6 py-4 text-center">Seat</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Payment</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- User --}}
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $booking->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->order_id }}</p>
                            </td>

                            {{-- Route --}}
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-700 truncate max-w-[220px]">
                                    {{ $booking->schedule->route->origin?->name ?? '-' }}
                                    →
                                    {{ $booking->schedule->route->destination?->name ?? '-' }}
                                </p>
                            </td>

                            {{-- Driver --}}
                            <td class="px-6 py-4">
                                {{ $booking->schedule->driver->user->name ?? '-' }}
                            </td>

                            {{-- Vehicle --}}
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $booking->schedule->vehicle->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->schedule->vehicle->plate_number ?? '-' }}</p>
                            </td>

                            {{-- Seat --}}
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                    {{ $booking->total_seat }}
                                </span>
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4 text-right font-semibold text-gray-700">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>

                            {{-- Booking Status --}}
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusMap = [
                                        'paid' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                    ];
                                    $statusClass = $statusMap[$booking->status] ?? 'bg-red-100 text-red-700';
                                    $statusLabel = ucfirst($booking->status);
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Payment Status --}}
                            <td class="px-6 py-4 text-center">
                                @php
                                    $paymentMap = [
                                        'paid' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'expired' => 'bg-gray-100 text-gray-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                    ];
                                    $paymentClass = $paymentMap[$booking->payment_status] ?? 'bg-red-100 text-red-700';
                                    $paymentLabel = ucfirst($booking->payment_status);
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $paymentClass }}">
                                    {{ $paymentLabel }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-xs font-medium hover:bg-blue-700 transition">
                                        Detail
                                    </a>

                                    @if($booking->status !== 'cancelled' && $booking->payment_status === 'paid')
                                        <form action="{{ route('bookings.refund', $booking) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin melakukan refund booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition">
                                                Refund
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2a4 4 0 014-4h6" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7h18M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700">Tidak ada data booking</h3>
                                    <p class="text-sm text-gray-500 mt-1">Belum ada booking yang dapat ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($bookings->hasPages())
            <div class="border-t px-6 py-4">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>
@endsection