@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('routes.index') }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Buat Route</h1>
                <p class="text-sm text-gray-500 mt-0.5">Klik peta untuk menentukan titik asal dan tujuan</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('routes.store') }}" id="routeForm">
                @csrf

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-red-700 mb-1">Terdapat kesalahan pada input:</p>
                        <ul class="text-sm text-red-600 space-y-0.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Name Inputs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                                Titik Asal (Origin)
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" name="origin_name" id="origin_name" value="{{ old('origin_name') }}"
                                placeholder="Klik peta atau ketik nama lokasi..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm">

                            <div id="origin_suggestions"
                                class="absolute z-50 bg-white border w-full mt-1 rounded shadow hidden"></div>
                        </div>
                        @error('origin_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                                Titik Tujuan (Destination)
                            </span>
                        </label>
                        <input type="text" name="destination_name" id="destination_name"
                            value="{{ old('destination_name') }}" placeholder="Klik peta atau ketik nama lokasi..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('destination_name') border-red-400 @enderror"
                            required>
                        <div id="destination_suggestions"
                            class="absolute z-50 bg-white border w-full mt-1 rounded shadow hidden"></div>
                        @error('destination_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Hidden Coordinate Fields --}}
                <input type="hidden" name="origin_lat" id="origin_lat" value="{{ old('origin_lat') }}">
                <input type="hidden" name="origin_lng" id="origin_lng" value="{{ old('origin_lng') }}">
                <input type="hidden" name="destination_lat" id="destination_lat" value="{{ old('destination_lat') }}">
                <input type="hidden" name="destination_lng" id="destination_lng" value="{{ old('destination_lng') }}">

                {{-- Map Status Badge --}}
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-500">
                        Klik pertama: <span class="text-emerald-600 font-medium">Origin</span> &nbsp;•&nbsp;
                        Klik kedua: <span class="text-red-500 font-medium">Destination</span> &nbsp;•&nbsp;
                        Klik berikutnya: <span class="text-purple-500 font-medium">Tambah Stop</span> &nbsp;•&nbsp;
                        Double-click: <span class="text-gray-500 font-medium">Reset</span>
                    </p>
                    <div id="mapStatus" class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                        Belum ada titik dipilih
                    </div>
                </div>

                {{-- Map --}}
                <div id="map" class="w-full h-[420px] rounded-xl overflow-hidden border border-gray-200 mb-4 z-0">
                </div>

                {{-- Coordinate Preview --}}
                <div id="coordPreview" class="hidden grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">
                    <div class="bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-emerald-600 mb-1 uppercase tracking-wide">Origin</p>
                        <p id="originCoordText" class="text-sm font-mono text-emerald-800">—</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-red-500 mb-1 uppercase tracking-wide">Destination</p>
                        <p id="destCoordText" class="text-sm font-mono text-red-700">—</p>
                    </div>
                </div>
                {{-- Stops Preview --}}
                <div id="stopsPreview" class="hidden mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide">
                            Stops Tambahan
                        </p>
                        <button type="button" id="clearStopsBtn"
                            class="text-xs text-red-400 hover:text-red-600 transition">
                            Hapus semua stop
                        </button>
                    </div>
                    <div id="stopsList" class="space-y-2"></div>
                </div>

                {{-- Hidden stops JSON --}}
                <input type="hidden" name="stops" id="stopsInput" value="[]">
                {{-- Submit --}}
                <div class="flex items-center gap-3">
                    <button type="submit" id="submitBtn"
                        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Route
                    </button>
                    <a href="{{ route('routes.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container {
            font-family: inherit;
        }

        .origin-icon {
            background: #34d399;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        .dest-icon {
            background: #f87171;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function setupAutocomplete(inputId, suggestionId, latId, lngId) {
                const input = document.getElementById(inputId);
                const box = document.getElementById(suggestionId);

                let timeout = null;

                input.addEventListener('input', function() {
                    clearTimeout(timeout);

                    const query = input.value;
                    if (query.length < 3) {
                        box.classList.add('hidden');
                        return;
                    }

                    timeout = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json`)
                            .then(res => res.json())
                            .then(data => {
                                box.innerHTML = '';

                                data.slice(0, 5).forEach(place => {
                                    const item = document.createElement('div');
                                    item.className =
                                        'p-2 text-sm hover:bg-gray-100 cursor-pointer';
                                    item.textContent = place.display_name;

                                    // item.onclick = () => {
                                    //     input.value = place.display_name;
                                    //     document.getElementById(latId).value = place
                                    //         .lat;
                                    //     document.getElementById(lngId).value = place
                                    //         .lon;

                                    //     // pindahin map
                                    //     map.setView([place.lat, place.lon], 13);

                                    //     box.classList.add('hidden');
                                    // };
                                    item.onclick = () => {
                                        const lat = parseFloat(place.lat);
                                        const lng = parseFloat(place.lon);

                                        input.value = place.display_name;

                                        document.getElementById(latId).value = lat;
                                        document.getElementById(lngId).value = lng;

                                        map.setView([lat, lng], 13);

                                        // 🔥 pakai engine yang sama
                                        handlePointSelection(lat, lng, 'search');

                                        box.classList.add('hidden');
                                    };

                                    box.appendChild(item);
                                });

                                box.classList.remove('hidden');
                            });
                    }, 400);
                });

                document.addEventListener('click', (e) => {
                    if (!box.contains(e.target) && e.target !== input) {
                        box.classList.add('hidden');
                    }
                });
            }
            // ── Map init ─────────────────────────────────────────────
            const map = L.map('map').setView([-2.5, 118.0], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            setupAutocomplete('origin_name', 'origin_suggestions', 'origin_lat', 'origin_lng');
            setupAutocomplete('destination_name', 'destination_suggestions', 'destination_lat', 'destination_lng');

            // ── Custom markers ────────────────────────────────────────
            function createIcon(color) {
                return L.divIcon({
                    className: '',
                    html: `<div style="width:16px;height:16px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.25);"></div>`,
                    iconSize: [16, 16],
                    iconAnchor: [8, 8],
                    popupAnchor: [0, -12]
                });
            }

            let originMarker = null;
            let destinationMarker = null;
            let routeLayer = null;
            let clickCount = 0;
            let stopMarkers = [];
            let stops = [];
            const statusEl = document.getElementById('mapStatus');
            const coordPreview = document.getElementById('coordPreview');
            const originText = document.getElementById('originCoordText');
            const destText = document.getElementById('destCoordText');

            function setStatus(text, color = 'text-gray-400') {
                statusEl.textContent = text;
                statusEl.className = `text-xs font-medium bg-gray-100 px-3 py-1 rounded-full ${color}`;
            }

            // ── Reverse geocode ────────────────────────────────────────
            function reverseGeocode(lat, lng, inputId) {
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(r => r.json())
                    .then(data => {
                        const name = data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                        document.getElementById(inputId).value = name;
                    })
                    .catch(() => {
                        document.getElementById(inputId).value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                    });
            }

            function handlePointSelection(lat, lng, source = 'map') {

                if (clickCount === 0) {
                    if (originMarker) map.removeLayer(originMarker);

                    originMarker = L.marker([lat, lng], {
                        icon: createIcon('#34d399')
                    }).addTo(map).bindPopup('<b>Origin</b>').openPopup();

                    document.getElementById('origin_lat').value = lat;
                    document.getElementById('origin_lng').value = lng;

                    if (source === 'map') {
                        reverseGeocode(lat, lng, 'origin_name');
                    }

                    setStatus('Origin dipilih — pilih destination', 'text-emerald-600');
                    clickCount = 1;

                } else if (clickCount === 1) {
                    if (destinationMarker) map.removeLayer(destinationMarker);

                    destinationMarker = L.marker([lat, lng], {
                        icon: createIcon('#f87171')
                    }).addTo(map).bindPopup('<b>Destination</b>').openPopup();

                    document.getElementById('destination_lat').value = lat;
                    document.getElementById('destination_lng').value = lng;

                    if (source === 'map') {
                        reverseGeocode(lat, lng, 'destination_name');
                    }

                    setStatus('Kedua titik dipilih ✓', 'text-blue-600');
                    clickCount = 2;

                    const bounds = L.latLngBounds(
                        originMarker.getLatLng(),
                        destinationMarker.getLatLng()
                    );
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });

                } else {
                    const stopMarker = L.marker([lat, lng]).addTo(map);
                    stopMarkers.push(stopMarker);
                    stops.push({
                        lat,
                        lng
                    });
                    renderStops();

                    setStatus(`Stop ditambahkan (${stops.length})`, 'text-purple-600');
                }
            }
            // ── Map click ─────────────────────────────────────────────
            map.on('click', function(e) {
                handlePointSelection(e.latlng.lat, e.latlng.lng, 'map');
                // const {
                //     lat,
                //     lng
                // } = e.latlng;

                // if (clickCount === 0) {
                //     // Set origin
                //     if (originMarker) map.removeLayer(originMarker);
                //     originMarker = L.marker([lat, lng], {
                //             icon: createIcon('#34d399')
                //         })
                //         .addTo(map).bindPopup('<b>Origin</b>').openPopup();

                //     document.getElementById('origin_lat').value = lat;
                //     document.getElementById('origin_lng').value = lng;
                //     originText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                //     coordPreview.classList.remove('hidden');
                //     coordPreview.classList.add('grid');

                //     reverseGeocode(lat, lng, 'origin_name');
                //     setStatus('Origin dipilih — pilih destination', 'text-emerald-600');
                //     clickCount = 1;

                // } else if (clickCount === 1) {
                //     // Set destination
                //     if (destinationMarker) map.removeLayer(destinationMarker);
                //     destinationMarker = L.marker([lat, lng], {
                //             icon: createIcon('#f87171')
                //         })
                //         .addTo(map).bindPopup('<b>Destination</b>').openPopup();

                //     document.getElementById('destination_lat').value = lat;
                //     document.getElementById('destination_lng').value = lng;
                //     destText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                //     reverseGeocode(lat, lng, 'destination_name');
                //     setStatus('Kedua titik dipilih ✓', 'text-blue-600');
                //     clickCount = 2;

                //     // Fit map to both markers
                //     const bounds = L.latLngBounds(originMarker.getLatLng(), destinationMarker.getLatLng());
                //     map.fitBounds(bounds, {
                //         padding: [50, 50]
                //     });
                // } else {
                //     const stopMarker = L.marker([lat, lng]).addTo(map);
                //     stopMarkers.push(stopMarker);
                //     stops.push({
                //         lat,
                //         lng
                //     });
                //     renderStops();
                //     setStatus(`Stop ditambahkan (${stops.length})`, 'text-purple-600');
                // }
            });

            // ── Reset button (dbl-click) ───────────────────────────────
            map.on('dblclick', function() {
                if (originMarker) {
                    map.removeLayer(originMarker);
                    originMarker = null;
                }
                if (destinationMarker) {
                    map.removeLayer(destinationMarker);
                    destinationMarker = null;
                }
                if (stops) {
                    stopMarkers.forEach(m => map.removeLayer(m));
                    stopMarkers = [];
                    stops = [];
                    clickCount = 0;
                    renderStops();
                }
                if (routeLayer) {
                    map.removeLayer(routeLayer);
                    routeLayer = null;
                }

                ['origin_lat', 'origin_lng', 'destination_lat', 'destination_lng'].forEach(id => {
                    document.getElementById(id).value = '';
                });
                ['origin_name', 'destination_name'].forEach(id => {
                    document.getElementById(id).value = '';
                });

                coordPreview.classList.add('hidden');
                coordPreview.classList.remove('grid');
                originText.textContent = '—';
                destText.textContent = '—';
                clickCount = 0;
                setStatus('Reset — pilih titik awal', 'text-gray-400');
            });
            // ── Render stops list ─────────────────────────────────────
            const stopsPreview = document.getElementById('stopsPreview');
            const stopsList = document.getElementById('stopsList');
            const stopsInput = document.getElementById('stopsInput');

            function renderStops() {
                stopsList.innerHTML = '';
                stopsInput.value = JSON.stringify(stops);

                if (stops.length === 0) {
                    stopsPreview.classList.add('hidden');
                    return;
                }

                stopsPreview.classList.remove('hidden');

                stops.forEach((s, i) => {
                    const row = document.createElement('div');
                    row.className =
                        'flex items-center justify-between bg-purple-50 border border-purple-100 rounded-lg px-4 py-2.5';
                    row.innerHTML = `
            <div>
                <span class="text-xs font-semibold text-purple-500 uppercase tracking-wide mr-2">Stop ${i + 1}</span>
                <span class="text-sm font-mono text-purple-800">${s.lat.toFixed(6)}, ${s.lng.toFixed(6)}</span>
            </div>
            <button type="button" data-index="${i}"
                class="remove-stop text-xs text-red-400 hover:text-red-600 transition ml-3">
                Hapus
            </button>
        `;
                    stopsList.appendChild(row);
                });

                // Hapus stop individual
                stopsList.querySelectorAll('.remove-stop').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.dataset.index);
                        map.removeLayer(stopMarkers[idx]);
                        stopMarkers.splice(idx, 1);
                        stops.splice(idx, 1);
                        renderStops();
                        setStatus(stops.length > 0 ? `Stop: ${stops.length}` :
                            'Kedua titik dipilih ✓',
                            stops.length > 0 ? 'text-purple-600' : 'text-blue-600');
                    });
                });
            }

            // Hapus semua stops
            document.getElementById('clearStopsBtn').addEventListener('click', function() {
                stopMarkers.forEach(m => map.removeLayer(m));
                stopMarkers = [];
                stops = [];
                renderStops();
                setStatus('Kedua titik dipilih ✓', 'text-blue-600');
            });
        });
    </script>
@endpush
