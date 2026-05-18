@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('routes.index') }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Edit Route</h1>
                <p class="text-sm text-gray-500 mt-0.5">Ubah nama, titik asal, tujuan, atau via stop</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('routes.update', $route->id) }}" id="routeForm">
                @csrf
                @method('PUT')

                {{-- Warning booking --}}
                <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                    <p class="text-sm text-amber-700">Route tidak dapat diedit jika sudah memiliki booking aktif. Pengecekan dilakukan otomatis saat menyimpan.</p>
                </div>

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

                {{-- Route Name --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Route</label>
                    <input type="text" name="name" value="{{ old('name', $route->name) }}"
                        placeholder="Contoh: Bandung - Jakarta"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('name') border-red-400 @enderror"
                        required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Origin & Destination Search --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                                Titik Asal (Origin)
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" id="origin_name" placeholder="Klik peta atau ketik nama lokasi..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400 transition">
                            <div id="origin_suggestions"
                                class="absolute z-50 bg-white border border-gray-200 w-full mt-1 rounded-lg shadow-lg hidden max-h-52 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                                Titik Tujuan (Destination)
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" id="destination_name" placeholder="Klik peta atau ketik nama lokasi..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition">
                            <div id="destination_suggestions"
                                class="absolute z-50 bg-white border border-gray-200 w-full mt-1 rounded-lg shadow-lg hidden max-h-52 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden Coordinate Fields --}}
                <input type="hidden" id="origin_lat">
                <input type="hidden" id="origin_lng">
                <input type="hidden" id="destination_lat">
                <input type="hidden" id="destination_lng">

                {{-- Map Status --}}
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-500">
                        Klik 1×: <span class="text-emerald-600 font-medium">Origin</span> &nbsp;•&nbsp;
                        Klik 2×: <span class="text-red-500 font-medium">Destination</span> &nbsp;•&nbsp;
                        Klik 3×+: <span class="text-purple-500 font-medium">Via Stop</span> &nbsp;•&nbsp;
                        Double-click: <span class="text-gray-500 font-medium">Reset</span>
                    </p>
                    <div id="mapStatus" class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                        Memuat data...
                    </div>
                </div>

                {{-- Map --}}
                <div id="map" class="w-full h-[420px] rounded-xl overflow-hidden border border-gray-200 mb-4 z-0"></div>

                {{-- VIA STOP SEARCH --}}
                <div id="viaSearchWrapper" class="hidden mb-5">
                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm font-medium text-purple-700">Tambah Via Stop</span>
                            <span class="text-xs bg-purple-100 text-purple-500 px-2 py-0.5 rounded-full ml-1">opsional</span>
                        </div>
                        <div class="flex gap-2 items-start">
                            <div class="relative flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                                </svg>
                                <input type="text" id="viaSearchInput"
                                    placeholder="Cari lokasi via stop... (min 3 karakter)"
                                    class="w-full border border-purple-200 bg-white rounded-lg pl-8 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition">
                                <div id="viaSuggestions"
                                    class="absolute z-50 bg-white border border-gray-200 w-full mt-1 rounded-lg shadow-lg hidden max-h-52 overflow-y-auto">
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-purple-400 mt-2">Via stop disisipkan secara otomatis sebelum titik tujuan. Atau klik langsung di peta.</p>
                    </div>
                </div>

                {{-- Client-side error --}}
                <div id="clientError" class="hidden mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
                    <p id="clientErrorMsg" class="text-sm text-red-600"></p>
                </div>

                {{-- Stops List --}}
                <div id="stopsPreview" class="hidden mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Daftar Stop</p>
                        <button type="button" id="clearStopsBtn" class="text-xs text-red-400 hover:text-red-600 transition hidden">
                            Hapus semua via-stop
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="text-xs text-gray-400">Toggle per stop:</span>
                        <span class="inline-flex items-center gap-1 text-xs text-blue-500">
                            <span class="w-2 h-2 rounded-sm bg-blue-400 inline-block"></span> Pickup
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-orange-500">
                            <span class="w-2 h-2 rounded-sm bg-orange-400 inline-block"></span> Dropoff
                        </span>
                    </div>
                    <div id="stopsList" class="space-y-2"></div>
                </div>

                {{-- Hidden stops JSON --}}
                <input type="hidden" name="stops" id="stopsInput" value="[]">

                {{-- Submit --}}
                <div class="flex items-center gap-3">
                    <button type="submit" id="submitBtn"
                        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Route
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
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ── PRELOADED DATA FROM SERVER ─────────────────────────────────
            let stops = @json(json_decode($stopsJson));
            let stopMarkers = [];
            let routeLayer = null;

            // clickCount diinfer dari jumlah stops yang sudah ada
            let clickCount = stops.length >= 2 ? 2 : stops.length;

            // ── MAP INIT ───────────────────────────────────────────────────
            const map = L.map('map').setView([-2.5, 118.0], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // ── HELPERS ────────────────────────────────────────────────────
            const statusEl         = document.getElementById('mapStatus');
            const stopsInput       = document.getElementById('stopsInput');
            const stopsPreview     = document.getElementById('stopsPreview');
            const stopsList        = document.getElementById('stopsList');
            const clearBtn         = document.getElementById('clearStopsBtn');
            const clientError      = document.getElementById('clientError');
            const clientErrorMsg   = document.getElementById('clientErrorMsg');
            const viaSearchWrapper = document.getElementById('viaSearchWrapper');
            const viaSearchInput   = document.getElementById('viaSearchInput');
            const viaSuggestions   = document.getElementById('viaSuggestions');

            function setStatus(text, color = 'text-gray-400') {
                statusEl.textContent = text;
                statusEl.className = `text-xs font-medium bg-gray-100 px-3 py-1 rounded-full ${color}`;
            }

            function showError(msg) { clientErrorMsg.textContent = msg; clientError.classList.remove('hidden'); }
            function hideError()    { clientError.classList.add('hidden'); }

            function createIcon(color) {
                return L.divIcon({
                    className: '',
                    html: `<div style="width:16px;height:16px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3);"></div>`,
                    iconSize: [16, 16], iconAnchor: [8, 8], popupAnchor: [0, -12]
                });
            }

            function iconForIndex(i, total) {
                if (i === 0)          return createIcon('#34d399');
                if (i === total - 1)  return createIcon('#f87171');
                return createIcon('#a78bfa');
            }

            function labelForIndex(i, total) {
                if (i === 0)          return 'Origin';
                if (i === total - 1)  return 'Destination';
                return `Via Stop ${i}`;
            }

            // ── SYNC ───────────────────────────────────────────────────────
            function syncHiddenInput() {
                stopsInput.value = JSON.stringify(stops);
            }

            function rebuildMarkers() {
                stopMarkers.forEach(m => map.removeLayer(m));
                stopMarkers = [];
                stops.forEach((s, i) => {
                    const m = L.marker([s.lat, s.lng], { icon: iconForIndex(i, stops.length) })
                        .addTo(map)
                        .bindPopup(`<b>${labelForIndex(i, stops.length)}</b><br><span class="text-xs">${s.name}</span>`);
                    stopMarkers.push(m);
                });
            }

            // ── RENDER STOPS LIST UI ───────────────────────────────────────
            function renderStopsList() {
                stopsList.innerHTML = '';

                if (stops.length === 0) {
                    stopsPreview.classList.add('hidden');
                    clearBtn.classList.add('hidden');
                    return;
                }

                stopsPreview.classList.remove('hidden');
                clearBtn.classList.toggle('hidden', stops.length <= 2);

                stops.forEach((s, i) => {
                    const isOrigin = i === 0;
                    const isDest   = i === stops.length - 1 && stops.length > 1;
                    const isVia    = !isOrigin && !isDest;

                    const label     = labelForIndex(i, stops.length);
                    const borderCls = isOrigin ? 'border-emerald-200 bg-emerald-50' :
                                      isDest   ? 'border-red-200 bg-red-50' :
                                                 'border-purple-200 bg-purple-50';
                    const badgeCls  = isOrigin ? 'text-emerald-700 bg-emerald-100' :
                                      isDest   ? 'text-red-600 bg-red-100' :
                                                 'text-purple-700 bg-purple-100';

                    const row = document.createElement('div');
                    row.className = `flex items-start gap-3 border rounded-xl px-4 py-3 ${borderCls}`;
                    row.innerHTML = `
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full ${badgeCls}">${label}</span>
                                ${isVia ? `<button type="button" data-index="${i}" class="remove-stop ml-auto text-xs text-red-400 hover:text-red-600 transition">Hapus</button>` : ''}
                            </div>
                            <p class="text-sm text-gray-700 truncate">${s.name}</p>
                            <p class="text-xs font-mono text-gray-400 mt-0.5">${Number(s.lat).toFixed(5)}, ${Number(s.lng).toFixed(5)}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                    <input type="checkbox" class="pickup-toggle rounded accent-blue-500" data-index="${i}" ${s.is_pickup ? 'checked' : ''}>
                                    <span class="text-xs text-blue-600 font-medium">Pickup</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                    <input type="checkbox" class="dropoff-toggle rounded accent-orange-500" data-index="${i}" ${s.is_dropoff ? 'checked' : ''}>
                                    <span class="text-xs text-orange-500 font-medium">Dropoff</span>
                                </label>
                            </div>
                        </div>
                    `;
                    stopsList.appendChild(row);
                });

                stopsList.querySelectorAll('.pickup-toggle').forEach(cb => {
                    cb.addEventListener('change', function () {
                        stops[parseInt(this.dataset.index)].is_pickup = this.checked;
                        syncHiddenInput(); validateStops();
                    });
                });
                stopsList.querySelectorAll('.dropoff-toggle').forEach(cb => {
                    cb.addEventListener('change', function () {
                        stops[parseInt(this.dataset.index)].is_dropoff = this.checked;
                        syncHiddenInput(); validateStops();
                    });
                });
                stopsList.querySelectorAll('.remove-stop').forEach(btn => {
                    btn.addEventListener('click', function () {
                        stops.splice(parseInt(this.dataset.index), 1);
                        rebuildMarkers(); syncHiddenInput(); renderStopsList(); validateStops();
                        const viaCount = stops.length - 2;
                        setStatus(viaCount > 0 ? `Via-stop: ${viaCount}` : 'Kedua titik dipilih ✓',
                                  viaCount > 0 ? 'text-purple-600' : 'text-blue-600');
                    });
                });
            }

            // ── VALIDATION ─────────────────────────────────────────────────
            function validateStops() {
                hideError();
                if (stops.length < 2) return false;

                if (!stops.some(s => s.is_pickup))  { showError('Minimal harus ada satu stop bertipe Pickup.');   return false; }
                if (!stops.some(s => s.is_dropoff)) { showError('Minimal harus ada satu stop bertipe Dropoff.'); return false; }

                const names = stops.map(s => s.name.trim().toLowerCase());
                if (names.some((n, i) => names.indexOf(n) !== i)) {
                    showError('Terdapat stop dengan nama yang sama (duplicate).');
                    return false;
                }
                return true;
            }

            // ── FORM SUBMIT GUARD ──────────────────────────────────────────
            document.getElementById('routeForm').addEventListener('submit', function (e) {
                if (stops.length < 2) { e.preventDefault(); showError('Pilih minimal origin dan destination di peta.'); return; }
                if (!validateStops()) e.preventDefault();
            });

            // ── REVERSE GEOCODE ────────────────────────────────────────────
            function reverseGeocode(lat, lng, callback) {
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(r => r.json())
                    .then(d => callback(d.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`))
                    .catch(() => callback(`${lat.toFixed(5)}, ${lng.toFixed(5)}`));
            }

            // ── CORE: ADD / REPLACE POINT ──────────────────────────────────
            function handlePointSelection(lat, lng, resolvedName, source = 'map') {
                const addStop = (name) => {
                    const stop = { name, address: name, lat, lng, is_pickup: true, is_dropoff: true };

                    if (clickCount === 0) {
                        stops[0] = stop;
                        document.getElementById('origin_lat').value = lat;
                        document.getElementById('origin_lng').value = lng;
                        document.getElementById('origin_name').value = name;
                        clickCount = 1;
                        setStatus('Origin dipilih — pilih destination', 'text-emerald-600');

                    } else if (clickCount === 1) {
                        stops[1] = stop;
                        document.getElementById('destination_lat').value = lat;
                        document.getElementById('destination_lng').value = lng;
                        document.getElementById('destination_name').value = name;
                        clickCount = 2;
                        setStatus('Kedua titik dipilih ✓ — klik lagi atau cari via-stop', 'text-blue-600');
                        viaSearchWrapper.classList.remove('hidden');
                        map.fitBounds(L.latLngBounds([stops[0].lat, stops[0].lng], [stops[1].lat, stops[1].lng]), { padding: [50, 50] });

                    } else {
                        stops.splice(stops.length - 1, 0, stop);
                        setStatus(`Via-stop ditambahkan (${stops.length - 2})`, 'text-purple-600');
                    }

                    rebuildMarkers(); syncHiddenInput(); renderStopsList(); validateStops();
                };

                resolvedName ? addStop(resolvedName) : reverseGeocode(lat, lng, addStop);
            }

            // ── MAP EVENTS ─────────────────────────────────────────────────
            map.on('click', e => handlePointSelection(e.latlng.lat, e.latlng.lng, null, 'map'));
            map.on('dblclick', () => resetAll());

            // ── RESET ──────────────────────────────────────────────────────
            function resetAll() {
                stopMarkers.forEach(m => map.removeLayer(m));
                stopMarkers = []; stops = []; clickCount = 0;
                if (routeLayer) { map.removeLayer(routeLayer); routeLayer = null; }
                ['origin_lat','origin_lng','destination_lat','destination_lng'].forEach(id => document.getElementById(id).value = '');
                ['origin_name','destination_name'].forEach(id => document.getElementById(id).value = '');
                viaSearchWrapper.classList.add('hidden');
                viaSearchInput.value = ''; viaSuggestions.classList.add('hidden');
                syncHiddenInput(); renderStopsList(); hideError();
                setStatus('Reset — pilih titik awal', 'text-gray-400');
            }

            // ── CLEAR VIA-STOPS ────────────────────────────────────────────
            clearBtn.addEventListener('click', function () {
                if (stops.length <= 2) return;
                stops = [stops[0], stops[stops.length - 1]];
                rebuildMarkers(); syncHiddenInput(); renderStopsList(); validateStops();
                setStatus('Kedua titik dipilih ✓', 'text-blue-600');
            });

            // ── VIA STOP SEARCH ────────────────────────────────────────────
            (function setupViaSearch() {
                let timeout = null;
                viaSearchInput.addEventListener('input', function () {
                    clearTimeout(timeout);
                    const query = viaSearchInput.value.trim();
                    if (query.length < 3) { viaSuggestions.classList.add('hidden'); return; }
                    timeout = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5`)
                            .then(r => r.json())
                            .then(data => {
                                viaSuggestions.innerHTML = '';
                                if (!data.length) { viaSuggestions.classList.add('hidden'); return; }
                                data.forEach(place => {
                                    const item = document.createElement('div');
                                    item.className = 'p-2.5 text-sm hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-0 flex items-start gap-2';
                                    item.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-purple-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="text-gray-700">${place.display_name}</span>`;
                                    item.addEventListener('click', function () {
                                        if (stops.length < 2) { showError('Pilih origin dan destination terlebih dahulu.'); viaSuggestions.classList.add('hidden'); return; }
                                        stops.splice(stops.length - 1, 0, { name: place.display_name, address: place.display_name, lat: parseFloat(place.lat), lng: parseFloat(place.lon), is_pickup: true, is_dropoff: true });
                                        map.setView([parseFloat(place.lat), parseFloat(place.lon)], 13);
                                        rebuildMarkers(); syncHiddenInput(); renderStopsList(); validateStops();
                                        setStatus(`Via-stop ditambahkan (${stops.length - 2})`, 'text-purple-600');
                                        viaSearchInput.value = ''; viaSuggestions.classList.add('hidden');
                                    });
                                    viaSuggestions.appendChild(item);
                                });
                                viaSuggestions.classList.remove('hidden');
                            })
                            .catch(() => viaSuggestions.classList.add('hidden'));
                    }, 400);
                });
                document.addEventListener('click', e => {
                    if (!viaSuggestions.contains(e.target) && e.target !== viaSearchInput) viaSuggestions.classList.add('hidden');
                });
            })();

            // ── AUTOCOMPLETE (origin & destination) ───────────────────────
            function setupAutocomplete(inputId, suggestionId, latId, lngId) {
                const input = document.getElementById(inputId);
                const box   = document.getElementById(suggestionId);
                let timeout = null;
                input.addEventListener('input', function () {
                    clearTimeout(timeout);
                    const query = input.value.trim();
                    if (query.length < 3) { box.classList.add('hidden'); return; }
                    timeout = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5`)
                            .then(r => r.json())
                            .then(data => {
                                box.innerHTML = '';
                                if (!data.length) { box.classList.add('hidden'); return; }
                                data.forEach(place => {
                                    const item = document.createElement('div');
                                    item.className = 'p-2.5 text-sm hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0';
                                    item.textContent = place.display_name;
                                    item.onclick = () => {
                                        const lat = parseFloat(place.lat);
                                        const lng = parseFloat(place.lon);
                                        input.value = place.display_name;
                                        document.getElementById(latId).value = lat;
                                        document.getElementById(lngId).value = lng;
                                        map.setView([lat, lng], 13);

                                        if (inputId === 'origin_name' && clickCount === 0) {
                                            handlePointSelection(lat, lng, place.display_name, 'search');
                                        } else if (inputId === 'destination_name' && clickCount === 1) {
                                            handlePointSelection(lat, lng, place.display_name, 'search');
                                        } else {
                                            if (inputId === 'origin_name') {
                                                stops[0] = { name: place.display_name, address: place.display_name, lat, lng, is_pickup: stops[0]?.is_pickup ?? true, is_dropoff: stops[0]?.is_dropoff ?? true };
                                            } else if (inputId === 'destination_name') {
                                                const last = stops.length - 1;
                                                if (last >= 1) stops[last] = { name: place.display_name, address: place.display_name, lat, lng, is_pickup: stops[last]?.is_pickup ?? true, is_dropoff: stops[last]?.is_dropoff ?? true };
                                            }
                                            rebuildMarkers(); syncHiddenInput(); renderStopsList(); validateStops();
                                        }
                                        box.classList.add('hidden');
                                    };
                                    box.appendChild(item);
                                });
                                box.classList.remove('hidden');
                            });
                    }, 400);
                });
                document.addEventListener('click', e => { if (!box.contains(e.target) && e.target !== input) box.classList.add('hidden'); });
            }
            setupAutocomplete('origin_name', 'origin_suggestions', 'origin_lat', 'origin_lng');
            setupAutocomplete('destination_name', 'destination_suggestions', 'destination_lat', 'destination_lng');

            // ── INIT: PRELOAD STOPS FROM SERVER ────────────────────────────
            (function initFromServer() {
                if (stops.length === 0) {
                    setStatus('Belum ada titik dipilih', 'text-gray-400');
                    return;
                }

                // Isi field origin & destination dari data server
                if (stops[0]) {
                    document.getElementById('origin_name').value = stops[0].name;
                    document.getElementById('origin_lat').value  = stops[0].lat;
                    document.getElementById('origin_lng').value  = stops[0].lng;
                }
                if (stops.length >= 2) {
                    const last = stops[stops.length - 1];
                    document.getElementById('destination_name').value = last.name;
                    document.getElementById('destination_lat').value  = last.lat;
                    document.getElementById('destination_lng').value  = last.lng;
                    viaSearchWrapper.classList.remove('hidden');
                }

                rebuildMarkers();
                syncHiddenInput();
                renderStopsList();
                validateStops();

                // Fit peta ke semua marker
                if (stops.length >= 2) {
                    const bounds = L.latLngBounds(stops.map(s => [s.lat, s.lng]));
                    map.fitBounds(bounds, { padding: [50, 50] });
                } else {
                    map.setView([stops[0].lat, stops[0].lng], 13);
                }

                const viaCount = stops.length - 2;
                setStatus(
                    viaCount > 0 ? `Via-stop: ${viaCount} — edit atau tambah stop` : 'Kedua titik dipilih ✓ — edit jika perlu',
                    viaCount > 0 ? 'text-purple-600' : 'text-blue-600'
                );
            })();

        });
    </script>
@endpush