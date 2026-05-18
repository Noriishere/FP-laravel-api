@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Total pengguna</p>
                <h2 class="text-2xl font-semibold text-gray-800">{{ $totalUsers }}</h2>
            </div>
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Total pemesanan</p>
                <h2 class="text-2xl font-semibold text-gray-800">{{ $totalBookings }}</h2>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <i class="fa-solid fa-ticket text-sm"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Jadwal aktif</p>
                <h2 class="text-2xl font-semibold text-gray-800">{{ $activeSchedules }}</h2>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
                <i class="fa-solid fa-calendar-check text-sm"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Armada</p>
                <h2 class="text-2xl font-semibold text-gray-800">{{ $activeVehicles }}</h2>
            </div>
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500 flex-shrink-0">
                <i class="fa-solid fa-bus text-sm"></i>
            </div>
        </div>

    </div>

    {{-- CHART --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Grafik pemesanan</h3>
                <p class="text-xs text-gray-400 mt-0.5">30 hari terakhir</p>
            </div>
            <span class="text-xs text-gray-400 flex items-center gap-1.5">
                <i class="fa-solid fa-rotate-right text-gray-300"></i>
                Auto-refresh 30 dtk
            </span>
        </div>
        <canvas id="bookingChart" height="80"></canvas>
    </div>

    {{-- SCHEDULE MAP --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Schedule berikutnya</h3>

        @if ($nextSchedule && $nextSchedule->route)
            <div id="map" class="w-full h-56 rounded-lg overflow-hidden border border-gray-100"></div>
            <div class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                {{ $nextSchedule->route->origin?->name }}
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                {{ $nextSchedule->route->destination?->name }}
            </div>
        @else
            <div class="flex items-center gap-2 text-sm text-gray-400 py-4">
                <i class="fa-regular fa-calendar-xmark"></i>
                Tidak ada jadwal tersedia
            </div>
        @endif
    </div>

    {{-- DRIVER ONLINE & PENDING --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Driver online</h3>
            @forelse ($onlineDrivers as $driver)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold flex items-center justify-center">
                            {{ strtoupper(substr($driver->user->name, 0, 2)) }}
                        </div>
                        <span class="text-sm text-gray-700">{{ $driver->user->name }}</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Online
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Tidak ada driver online</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Driver belum disetujui</h3>
            @forelse ($pendingDrivers as $driver)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold flex items-center justify-center">
                            {{ strtoupper(substr($driver->user->name, 0, 2)) }}
                        </div>
                        <span class="text-sm text-gray-700">{{ $driver->user->name }}</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span> Pending
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Semua driver sudah approved</p>
            @endforelse
        </div>

    </div>

    {{-- TABEL PEMESANAN & ARMADA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Pemesanan terbaru</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="text-xs font-medium text-gray-400 pb-2 w-1/4">User</th>
                        <th class="text-xs font-medium text-gray-400 pb-2 w-2/5">Rute</th>
                        <th class="text-xs font-medium text-gray-400 pb-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($latestBookings as $booking)
                        <tr>
                            <td class="py-2.5 text-gray-700">{{ $booking->user->name ?? '-' }}</td>
                            <td class="py-2.5 text-gray-400 truncate max-w-0 text-xs">
                                {{ $booking->schedule->route->origin?->name ?? '-' }}
                                →
                                {{ $booking->schedule->route->destination?->name ?? '-' }}
                            </td>
                            <td class="py-2.5">
                                @if($booking->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        <i class="fa-solid fa-circle-check text-xs"></i> Confirmed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                        <i class="fa-solid fa-clock text-xs"></i> {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-sm text-gray-400">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Status armada</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="text-xs font-medium text-gray-400 pb-2 w-2/5">Nama</th>
                        <th class="text-xs font-medium text-gray-400 pb-2 w-1/4">Kapasitas</th>
                        <th class="text-xs font-medium text-gray-400 pb-2">Ketersediaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($vehicles as $vehicle)
                        <tr>
                            <td class="py-2.5 text-gray-700">{{ $vehicle->name }}</td>
                            <td class="py-2.5 text-gray-400 text-xs">{{ $vehicle->capacity }} kursi</td>
                            <td class="py-2.5">
                                @if ($vehicle->schedules->where('status', 'on-going')->count())
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                                        <i class="fa-solid fa-circle text-xs"></i> Dipakai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        <i class="fa-solid fa-circle-check text-xs"></i> Available
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-sm text-gray-400">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
    setInterval(() => location.reload(), 30000);
</script>

@if ($nextSchedule && $nextSchedule->route)
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([
            {{ $nextSchedule->route->origin?->lat }},
            {{ $nextSchedule->route->origin?->lng }}
        ], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const polyline = {!! $nextSchedule->route->polyline !!};
        L.polyline(polyline, { color: '#6366f1', weight: 3, opacity: 0.8 }).addTo(map);

        const stops = @json($nextSchedule->route->stops ?? []);
        stops.forEach((stop, index) => {
            const isStart = index === 0;
            const isEnd   = index === stops.length - 1;
            const bg = isStart ? '#34d399' : isEnd ? '#f87171' : '#a78bfa';

            const icon = L.divIcon({
                className: '',
                html: `<div style="width:14px;height:14px;background:${bg};border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.25);"></div>`,
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            L.marker([stop.lat, stop.lng], { icon }).addTo(map).bindPopup(stop.name);
        });

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
                label: 'Booking',
                data: {!! json_encode($data) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.08)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 4,
                pointHoverBackgroundColor: '#6366f1',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#9ca3af', maxTicksLimit: 8 }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { font: { size: 11 }, color: '#9ca3af' },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

@endsection