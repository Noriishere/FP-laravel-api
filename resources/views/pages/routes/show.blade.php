@extends('layouts.app')

@section('content')

<div class="space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ $route->name }}
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Detail route perjalanan shuttle
            </p>
        </div>

        <a href="{{ route('routes.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-sm px-4 py-2 rounded-lg transition">
            Kembali
        </a>

    </div>

    {{-- INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-xs text-gray-400 uppercase mb-1">
                Origin
            </p>

            <h3 class="text-lg font-semibold text-emerald-600">
                {{ $route->origin?->name }}
            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-xs text-gray-400 uppercase mb-1">
                Destination
            </p>

            <h3 class="text-lg font-semibold text-red-500">
                {{ $route->destination?->name }}
            </h3>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-xs text-gray-400 uppercase mb-1">
                Distance
            </p>

            <h3 class="text-lg font-semibold text-blue-600">
                {{ $route->distance }} km
            </h3>

        </div>

    </div>

    {{-- MAP --}}
    <div class="bg-white rounded-xl shadow p-5">

        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Route Map
        </h3>

        <div id="map"
            class="w-full h-[500px] rounded-xl border border-gray-200">
        </div>

    </div>

    {{-- STOPS --}}
    <div class="bg-white rounded-xl shadow p-5">

        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Stops
        </h3>

        <div class="space-y-3">

            @foreach($route->stops as $index => $stop)

                <div class="flex items-start gap-4">

                    <div class="
                        w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold
                        {{ $index === 0 ? 'bg-emerald-500' : '' }}
                        {{ $index === $route->stops->count() - 1 ? 'bg-red-500' : '' }}
                        {{ $index !== 0 && $index !== $route->stops->count() - 1 ? 'bg-purple-500' : '' }}
                    ">
                        {{ $index + 1 }}
                    </div>

                    <div class="flex-1">

                        <div class="flex items-center gap-2">

                            <h4 class="font-medium text-gray-800">
                                {{ $stop->name }}
                            </h4>

                            @if($stop->is_pickup)
                                <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                                    Pickup
                                </span>
                            @endif

                            @if($stop->is_dropoff)
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">
                                    Dropoff
                                </span>
                            @endif

                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $stop->address }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1 font-mono">
                            {{ $stop->lat }}, {{ $stop->lng }}
                        </p>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection

@push('styles')

<link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

@endpush

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const polyline = {!! $route->polyline !!};

        const stops = @json($route->stops);

        const map = L.map('map');

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '© OpenStreetMap contributors'
            }
        ).addTo(map);

        const line = L.polyline(polyline, {
            color: '#2563eb',
            weight: 5
        }).addTo(map);

        stops.forEach((stop, index) => {

            let color = '#9333ea';

            if (index === 0) {
                color = '#16a34a';
            }

            if (index === stops.length - 1) {
                color = '#dc2626';
            }

            const icon = L.divIcon({
                className: '',
                html: `
                    <div style="
                        background:${color};
                        color:white;
                        width:30px;
                        height:30px;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:12px;
                        font-weight:bold;
                        border:2px solid white;
                        box-shadow:0 2px 6px rgba(0,0,0,.3);
                    ">
                        ${index + 1}
                    </div>
                `,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            L.marker([stop.lat, stop.lng], {
                icon
            })
            .addTo(map)
            .bindPopup(`
                <div>
                    <strong>${stop.name}</strong>
                    <br>
                    <small>${stop.address ?? '-'}</small>
                </div>
            `);
        });

        map.fitBounds(line.getBounds(), {
            padding: [40, 40]
        });

    });

</script>

@endpush