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

            // ── Autocomplete helper ───────────────────────────────────────────
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
                        fetch(
                                `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`)
                            .then(res => res.json())
                            .then(data => {
                                box.innerHTML = '';
                                data.slice(0, 5).forEach(place => {
                                    const item = document.createElement('div');
                                    item.className =
                                        'p-2 text-sm hover:bg-gray-100 cursor-pointer';
                                    item.textContent = place.display_name;
                                    item.onclick = () => {
                                        const lat = parseFloat(place.lat);
                                        const lng = parseFloat(place.lon);
                                        input.value = place.display_name;
                                        document.getElementById(latId).value = lat;
                                        document.getElementById(lngId).value = lng;
                                        map.setView([lat, lng], 13);
                                        handlePointSelection(lat, lng, place
                                            .display_name, 'search');
                                        box.classList.add('hidden');
                                    };
                                    box.appendChild(item);
                                });
                                box.classList.remove('hidden');
                            });
                    }, 400);
                });

                document.addEventListener('click', (e) => {
                    if (!box.contains(e.target) && e.target !== input) box.classList.add('hidden');
                });
            }

            // ── Map init ──────────────────────────────────────────────────────
            const map = L.map('map').setView([-2.5, 118.0], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            setupAutocomplete('origin_name', 'origin_suggestions', 'origin_lat', 'origin_lng');
            setupAutocomplete('destination_name', 'destination_suggestions', 'destination_lat', 'destination_lng');

            // ── State ─────────────────────────────────────────────────────────
            /*
             * stops[] — array of objects yang dikirim ke backend:
             * {
             *   name      : string,
             *   address   : string|null,
             *   lat       : number,
             *   lng       : number,
             *   is_pickup : boolean,
             *   is_dropoff: boolean
             * }
             * Index 0 = origin, index 1 = destination, index 2+ = via-stops
             */
            let stops = []; // data stops (sesuai format backend)
            let stopMarkers = []; // L.Marker untuk setiap stop
            let routeLayer = null;
            let clickCount = 0; // 0=belum ada, 1=origin dipilih, 2+=destination + via

            const statusEl = document.getElementById('mapStatus');
            const stopsInput = document.getElementById('stopsInput'); // hidden input

            // ── Helpers ───────────────────────────────────────────────────────
            function setStatus(text, color = 'text-gray-400') {
                statusEl.textContent = text;
                statusEl.className = `text-xs font-medium bg-gray-100 px-3 py-1 rounded-full ${color}`;
            }

            function createIcon(color) {
                return L.divIcon({
                    className: '',
                    html: `<div style="width:16px;height:16px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.25);"></div>`,
                    iconSize: [16, 16],
                    iconAnchor: [8, 8],
                    popupAnchor: [0, -12]
                });
            }

            // Warna per role: origin=hijau, destination=merah, via=ungu
            function iconForIndex(index, total) {
                if (index === 0) return createIcon('#34d399'); // origin
                if (index === total - 1) return createIcon('#f87171'); // destination
                return createIcon('#a78bfa'); // via stop
            }

            function labelForIndex(index, total) {
                if (index === 0) return 'Origin';
                if (index === total - 1) return 'Destination';
                return `Stop ${index}`;
            }

            // Rebuild semua marker dari array stops[]
            function rebuildMarkers() {
                stopMarkers.forEach(m => map.removeLayer(m));
                stopMarkers = [];

                stops.forEach((s, i) => {
                    const marker = L.marker([s.lat, s.lng], {
                            icon: iconForIndex(i, stops.length)
                        })
                        .addTo(map)
                        .bindPopup(`<b>${labelForIndex(i, stops.length)}</b><br>${s.name}`);
                    stopMarkers.push(marker);
                });
            }

            // Sync hidden input → JSON yang diexpect backend
            function syncHiddenInput() {
                stopsInput.value = JSON.stringify(stops);
            }

            // ── Reverse geocode ───────────────────────────────────────────────
            function reverseGeocode(lat, lng, callback) {
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(r => r.json())
                    .then(data => callback(data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`))
                    .catch(() => callback(`${lat.toFixed(5)}, ${lng.toFixed(5)}`));
            }

            // ── Core: tambah / ganti titik ────────────────────────────────────
            function handlePointSelection(lat, lng, resolvedName = null, source = 'map') {

                const addStop = (name) => {
                    const stop = {
                        name: name,
                        address: name, // pakai display_name sebagai address; bisa dioverride
                        lat: lat,
                        lng: lng,
                        is_pickup: true,
                        is_dropoff: true
                    };

                    if (clickCount === 0) {
                        // Origin — selalu index 0
                        stops[0] = stop;
                        document.getElementById('origin_lat').value = lat;
                        document.getElementById('origin_lng').value = lng;
                        document.getElementById('origin_name').value = name;
                        clickCount = 1;
                        setStatus('Origin dipilih — pilih destination', 'text-emerald-600');

                    } else if (clickCount === 1) {
                        // Destination — selalu index 1 (atau geser yang lama ke via)
                        stops[1] = stop;
                        document.getElementById('destination_lat').value = lat;
                        document.getElementById('destination_lng').value = lng;
                        document.getElementById('destination_name').value = name;
                        clickCount = 2;
                        setStatus('Kedua titik dipilih ✓ — klik lagi untuk via-stop', 'text-blue-600');

                        // Fit ke kedua titik
                        const bounds = L.latLngBounds([stops[0].lat, stops[0].lng], [stops[1].lat, stops[1]
                            .lng]);
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        });

                    } else {
                        // Via stop — sisipkan sebelum destination (index terakhir)
                        stops.splice(stops.length - 1, 0, stop);
                        setStatus(`Via-stop ditambahkan (${stops.length - 2})`, 'text-purple-600');
                    }

                    rebuildMarkers();
                    syncHiddenInput();
                    renderStopsList();
                };

                if (resolvedName) {
                    addStop(resolvedName);
                } else {
                    reverseGeocode(lat, lng, addStop);
                }
            }

            // ── Map events ────────────────────────────────────────────────────
            map.on('click', function(e) {
                handlePointSelection(e.latlng.lat, e.latlng.lng, null, 'map');
            });

            map.on('dblclick', function() {
                resetAll();
            });

            // ── Reset ─────────────────────────────────────────────────────────
            function resetAll() {
                stopMarkers.forEach(m => map.removeLayer(m));
                stopMarkers = [];
                stops = [];
                clickCount = 0;

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

                syncHiddenInput();
                renderStopsList();
                setStatus('Reset — pilih titik awal', 'text-gray-400');
            }

            // ── Render daftar stops (UI) ──────────────────────────────────────
            const stopsPreview = document.getElementById('stopsPreview');
            const stopsList = document.getElementById('stopsList');

            function renderStopsList() {
                stopsList.innerHTML = '';

                if (stops.length === 0) {
                    stopsPreview.classList.add('hidden');
                    return;
                }

                stopsPreview.classList.remove('hidden');

                stops.forEach((s, i) => {
                    const isOrigin = i === 0;
                    const isDest = i === stops.length - 1;
                    const isVia = !isOrigin && !isDest;

                    const label = isOrigin ? 'Origin' : isDest ? 'Destination' : `Via ${i}`;
                    const color = isOrigin ? 'green' : isDest ? 'red' : 'purple';
                    const colorMap = {
                        green: 'text-emerald-600 bg-emerald-50 border-emerald-100',
                        red: 'text-red-500 bg-red-50 border-red-100',
                        purple: 'text-purple-600 bg-purple-50 border-purple-100',
                    };

                    const row = document.createElement('div');
                    row.className =
                        `flex items-center justify-between border rounded-lg px-4 py-2.5 ${colorMap[color]}`;
                    row.innerHTML = `
                <div class="flex-1 min-w-0">
                    <span class="text-xs font-semibold uppercase tracking-wide mr-2">${label}</span>
                    <span class="text-sm truncate block">${s.name}</span>
                    <span class="text-xs font-mono opacity-60">${s.lat.toFixed(5)}, ${s.lng.toFixed(5)}</span>
                </div>
                ${isVia ? `<button type="button" data-index="${i}"
                        class="remove-stop ml-3 text-xs text-red-400 hover:text-red-600 transition flex-shrink-0">Hapus</button>` : ''}
            `;
                    stopsList.appendChild(row);
                });

                // Hapus via-stop individual
                stopsList.querySelectorAll('.remove-stop').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.dataset.index);
                        stops.splice(idx, 1);
                        rebuildMarkers();
                        syncHiddenInput();
                        renderStopsList();
                        const viaCount = stops.length - 2;
                        setStatus(
                            viaCount > 0 ? `Via-stop: ${viaCount}` : 'Kedua titik dipilih ✓',
                            viaCount > 0 ? 'text-purple-600' : 'text-blue-600'
                        );
                    });
                });
            }

            // ── Clear via-stops button ────────────────────────────────────────
            document.getElementById('clearStopsBtn').addEventListener('click', function() {
                if (stops.length <= 2) return;
                // Pertahankan hanya origin (index 0) & destination (index terakhir)
                stops = [stops[0], stops[stops.length - 1]];
                rebuildMarkers();
                syncHiddenInput();
                renderStopsList();
                setStatus('Kedua titik dipilih ✓', 'text-blue-600');
            });

        });
    </script>
@endpush
