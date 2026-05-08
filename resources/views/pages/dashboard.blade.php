@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Grafik Pemesanan (30 Hari)
        </h3>

        <canvas id="bookingChart" height="100"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    </div>
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
            Schedule Berikutnya
        </h3>

        @if ($nextSchedule && $nextSchedule->route)
            <div id="map" class="w-full h-64 rounded-lg"></div>

            <p class="text-xs text-gray-500 mt-2">
                {{ $nextSchedule->route->origin?->name }} →
                {{ $nextSchedule->route->destination?->name }}
            </p>
        @else
            <p class="text-gray-400 text-sm">Tidak ada jadwal</p>
        @endif
    </div>
    {{-- TABLE --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- DRIVER ONLINE --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                Driver Online
            </h3>

            @forelse ($onlineDrivers as $driver)
                <div class="flex justify-between items-center border-b py-2">
                    <span>{{ $driver->user->name }}</span>
                    <span class="text-green-600 text-xs">
                        ● Online
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Tidak ada driver online</p>
            @endforelse
        </div>

        {{-- DRIVER PENDING --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                Driver Belum Disetujui
            </h3>

            @forelse ($pendingDrivers as $driver)
                <div class="flex justify-between items-center border-b py-2">
                    <span>{{ $driver->user->name }}</span>
                    <span class="text-yellow-600 text-xs">
                        ● Pending
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Semua driver sudah approved</p>
            @endforelse
        </div>

    </div>
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
                                {{ $booking->schedule->route->origin?->name ?? '-' }}
                                →
                                {{ $booking->schedule->route->destination?->name ?? '-' }}
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
                const map = L.map('map').setView([
                    {{ $nextSchedule->route->origin?->lat }},
                    {{ $nextSchedule->route->origin?->lng }}
                ], 6);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                const polyline = {!! $nextSchedule->route->polyline !!};
                const stops = @json($nextSchedule->route->stops ?? []);

                console.log("Stops:", stops);
                console.log(stops);
                const colors = ['#2563eb', '#16a34a', '#dc2626', '#9333ea', '#f59e0b'];

                const randomColor = colors[Math.floor(Math.random() * colors.length)];

                L.polyline(polyline, {
                    color: randomColor,
                    weight: 4
                }).addTo(map);
                const startIcon = L.icon({
                    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                    iconSize: [30, 30]
                });

                const midIcon = L.icon({
                    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
                    iconSize: [30, 30]
                });

                const endIcon = L.icon({
                    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    iconSize: [30, 30]
                });
                if (stops.length > 0) {
                    stops.forEach((stop, index) => {

                        const isStart = index === 0;
                        const isEnd = index === stops.length - 1;

                        let bg = '#3b82f6'; // default biru
                        if (isStart) bg = '#16a34a'; // hijau
                        if (isEnd) bg = '#dc2626'; // merah

                        const icon = L.divIcon({
                            className: '',
                            html: `
                <div style="
                    background:${bg};
                    color:white;
                    border-radius:50%;
                    width:28px;
                    height:28px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:12px;
                    font-weight:bold;
                    border:2px solid white;
                    box-shadow:0 2px 6px rgba(0,0,0,0.3);
                ">
                    ${index + 1}
                </div>
            `,
                            iconSize: [28, 28],
                            iconAnchor: [14, 14]
                        });

                        L.marker([stop.lat, stop.lng], {
                                icon
                            })
                            .addTo(map)
                            .bindPopup(stop.name);
                    });
                }

                map.fitBounds(polyline);

            });
        </script>
    @endif
    @push('scripts')
        <script>
            const ctx = document.getElementById('bookingChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Total Booking',
                        data: {!! json_encode($data) !!},
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
