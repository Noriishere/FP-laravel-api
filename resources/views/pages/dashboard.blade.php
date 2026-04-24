@extends('layouts.app')

@section('content')

{{-- STAT --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Pengguna</p>
        <h2 class="text-2xl font-semibold mt-1">{{ $totalUsers }}</h2>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Pemesanan</p>
        <h2 class="text-2xl font-semibold mt-1">{{ $totalBookings }}</h2>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Jadwal Aktif</p>
        <h2 class="text-2xl font-semibold mt-1">{{ $activeSchedules }}</h2>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Armada Aktif</p>
        <h2 class="text-2xl font-semibold mt-1">{{ $activeVehicles }}</h2>
    </div>

</div>

{{-- TABLE --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- PEMESANAN TERBARU --}}
    <div class="bg-white rounded-lg shadow p-5">

        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Pemesanan Terbaru
        </h3>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-2">User</th>
                    <th class="pb-2">Rute</th>
                    <th class="pb-2">Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($latestBookings as $booking)
                    <tr class="border-b">
                        <td class="py-2">{{ $booking->user->name ?? '-' }}</td>
                        <td class="py-2">
                            {{ $booking->schedule->route->start ?? '-' }}
                            →
                            {{ $booking->schedule->route->end ?? '-' }}
                        </td>
                        <td class="py-2">
                            {{ $booking->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- STATUS ARMADA --}}
    <div class="bg-white rounded-lg shadow p-5">

        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Status Armada
        </h3>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-2">Nama</th>
                    <th class="pb-2">Kapasitas</th>
                    <th class="pb-2">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr class="border-b">
                        <td class="py-2">{{ $vehicle->name }}</td>
                        <td class="py-2">{{ $vehicle->capacity }}</td>
                        <td class="py-2">
                            <span class="text-green-600 text-xs">
                                Aktif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection