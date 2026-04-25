@extends('layouts.app')

@section('content')
    {{-- STAT --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500">Total Pengguna</p>
                <h2 class="text-2xl font-bold">{{ $totalUsers }}</h2>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500">Total Pemesanan</p>
                <h2 class="text-2xl font-bold">{{ $totalBookings }}</h2>
            </div>
            <div class="bg-green-100 p-3 rounded-lg text-green-600">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500">Jadwal Aktif</p>
                <h2 class="text-2xl font-bold">{{ $activeSchedules }}</h2>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg text-yellow-600">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500">Armada</p>
                <h2 class="text-2xl font-bold">{{ $activeVehicles }}</h2>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg text-purple-600">
                <i class="fa-solid fa-bus"></i>
            </div>
        </div>

    </div>
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">
            Rute Terdekat
        </h3>

        @if ($nextSchedule && $nextSchedule->route)
            <div id="map" class="w-full h-64 rounded-lg"></div>

            <p class="text-xs text-gray-500 mt-2">
                {{ $nextSchedule->route->origin_name }} →
                {{ $nextSchedule->route->destination_name }}
            </p>
        @else
            <p class="text-gray-400 text-sm">Tidak ada jadwal</p>
        @endif
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
                                {{ $booking->schedule->route->origin_name ?? '-' }}
                                →
                                {{ $booking->schedule->route->destination_name ?? '-' }}
                            </td>
                            <td class="py-2">
                                {{ $booking->created_at->format('d M Y') }}
                            </td>
                            <td class="py-2">
                                <span
                                    class="text-xs px-2 py-1 rounded-full flex items-center gap-1 w-fit
        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">

                                    <i
                                        class="fa-solid 
            {{ $booking->status === 'confirmed' ? 'fa-circle-check' : 'fa-clock' }}">
                                    </i>

                                    {{ ucfirst($booking->status) }}
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
                            <td class="py-2">
                                @if ($vehicle->schedules->where('status', 'on-going')->count())
                                    <span class="text-yellow-600 text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-circle"></i> Dipakai
                                    </span>
                                @else
                                    <span class="text-green-600 text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Available
                                    </span>
                                @endif
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
    <script>
        setInterval(() => {
            location.reload();
        }, 30000); // refresh tiap 30 detik
    </script>
    @if ($nextSchedule && $nextSchedule->route)
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                if (typeof L === 'undefined') {
                    console.error('Leaflet belum ke-load');
                    return;
                }

                const map = L.map('map').setView([
                    {{ $nextSchedule->route->origin_lat }},
                    {{ $nextSchedule->route->origin_lng }}
                ], 7);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                const polyline = {!! $nextSchedule->route->polyline !!};

                L.polyline(polyline, {
                    color: 'blue'
                }).addTo(map);

                L.marker([{{ $nextSchedule->route->origin_lat }}, {{ $nextSchedule->route->origin_lng }}]).addTo(map);
                L.marker([{{ $nextSchedule->route->destination_lat }}, {{ $nextSchedule->route->destination_lng }}])
                    .addTo(map);

                map.fitBounds(polyline);

            });
        </script>
    @endif
@endsection
