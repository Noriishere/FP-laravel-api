@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('schedules.index') }}"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 shadow-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Buat Jadwal</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi detail jadwal perjalanan baru</p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <p class="text-sm font-medium text-red-700 mb-1">Terdapat kesalahan:</p>
        <ul class="text-sm text-red-600 space-y-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form method="POST" action="{{ route('schedules.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ROUTE --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Route</label>
                    <select name="route_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white @error('route_id') border-red-400 @enderror"
                        required>
                        <option value="">— Pilih Route —</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                {{ $route->origin?->name }} → {{ $route->destination?->name }}
                                ({{ $route->distance }} km)
                            </option>
                        @endforeach
                    </select>
                    @error('route_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DEPARTURE TIME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal & Waktu Keberangkatan</label>
                    <input type="datetime-local"
                        id="departure_time"
                        name="departure_time"
                        value="{{ old('departure_time') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('departure_time') border-red-400 @enderror"
                        required>
                    @error('departure_time')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DURATION --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (menit)</label>
                    <div class="relative">
                        <input type="number"
                            id="duration"
                            name="duration"
                            value="{{ old('duration') }}"
                            min="1"
                            placeholder="cth. 120"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition pr-16 @error('duration') border-red-400 @enderror"
                            required>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">menit</span>
                    </div>
                    {{-- Estimated arrival preview --}}
                    <p id="arrivalPreview" class="mt-1 text-xs text-gray-400 hidden">
                        Estimasi tiba: <span id="arrivalTime" class="font-medium text-gray-600"></span>
                    </p>
                    @error('duration')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DRIVER --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Driver</label>
                    <div class="relative">
                        <select name="driver_id"
                            id="driver_select"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white disabled:bg-gray-50 disabled:text-gray-400 @error('driver_id') border-red-400 @enderror"
                            required disabled>
                            <option value="">Isi tanggal & durasi dulu</option>
                        </select>
                        <div id="driverLoading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('driver_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- VEHICLE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kendaraan</label>
                    <div class="relative">
                        <select name="vehicle_id"
                            id="vehicle_select"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white disabled:bg-gray-50 disabled:text-gray-400 @error('vehicle_id') border-red-400 @enderror"
                            required disabled>
                            <option value="">Isi tanggal & durasi dulu</option>
                        </select>
                        <div id="vehicleLoading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('vehicle_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PRICE --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">Rp</span>
                        <input type="number"
                            name="price"
                            value="{{ old('price') }}"
                            min="0"
                            step="1000"
                            placeholder="0"
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('price') border-red-400 @enderror"
                            required>
                    </div>
                    @error('price')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary/90 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Jadwal
                </button>
                <a href="{{ route('schedules.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const departureInput = document.getElementById('departure_time');
    const durationInput  = document.getElementById('duration');
    const driverSelect   = document.getElementById('driver_select');
    const vehicleSelect  = document.getElementById('vehicle_select');
    const driverLoading  = document.getElementById('driverLoading');
    const vehicleLoading = document.getElementById('vehicleLoading');
    const arrivalPreview = document.getElementById('arrivalPreview');
    const arrivalTimeEl  = document.getElementById('arrivalTime');

    // ── Show estimated arrival time ───────────────────────────
    function updateArrivalPreview() {
        const departure = departureInput.value;
        const duration  = parseInt(durationInput.value);

        if (!departure || !duration || duration <= 0) {
            arrivalPreview.classList.add('hidden');
            return;
        }

        const departureDate = new Date(departure);
        const arrivalDate   = new Date(departureDate.getTime() + duration * 60 * 1000);

        const formatted = arrivalDate.toLocaleString('id-ID', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        arrivalTimeEl.textContent = formatted;
        arrivalPreview.classList.remove('hidden');
    }

    // ── Fetch available drivers & vehicles ────────────────────
    function loadAvailability() {
        const departure = departureInput.value;
        const duration  = durationInput.value;

        if (!departure || !duration || parseInt(duration) <= 0) return;

        updateArrivalPreview();

        const params = new URLSearchParams({ departure_time: departure, duration });

        // ── Drivers ───────────────────────────────────────────
        driverLoading.classList.remove('hidden');
        driverSelect.disabled = true;

        fetch(`/available-drivers?${params}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat driver');
                return res.json();
            })
            .then(data => {
                driverSelect.innerHTML = '';

                if (data.length === 0) {
                    driverSelect.innerHTML = '<option value="">Tidak ada driver tersedia</option>';
                    driverSelect.disabled = true;
                } else {
                    driverSelect.innerHTML = '<option value="">— Pilih Driver —</option>';
                    data.forEach(driver => {
                        const opt = document.createElement('option');
                        opt.value       = driver.id;
                        opt.textContent = driver.user?.name ?? `Driver #${driver.id}`;
                        // Re-select old value if validation failed
                        if ('{{ old('driver_id') }}' == driver.id) opt.selected = true;
                        driverSelect.appendChild(opt);
                    });
                    driverSelect.disabled = false;
                }
            })
            .catch(() => {
                driverSelect.innerHTML = '<option value="">Gagal memuat — coba lagi</option>';
                driverSelect.disabled = true;
            })
            .finally(() => driverLoading.classList.add('hidden'));

        // ── Vehicles ──────────────────────────────────────────
        vehicleLoading.classList.remove('hidden');
        vehicleSelect.disabled = true;

        fetch(`/available-vehicles?${params}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat kendaraan');
                return res.json();
            })
            .then(data => {
                vehicleSelect.innerHTML = '';

                if (data.length === 0) {
                    vehicleSelect.innerHTML = '<option value="">Tidak ada kendaraan tersedia</option>';
                    vehicleSelect.disabled = true;
                } else {
                    vehicleSelect.innerHTML = '<option value="">— Pilih Kendaraan —</option>';
                    data.forEach(vehicle => {
                        const opt = document.createElement('option');
                        opt.value       = vehicle.id;
                        opt.textContent = `${vehicle.name} (${vehicle.capacity} seat)`;
                        if ('{{ old('vehicle_id') }}' == vehicle.id) opt.selected = true;
                        vehicleSelect.appendChild(opt);
                    });
                    vehicleSelect.disabled = false;
                }
            })
            .catch(() => {
                vehicleSelect.innerHTML = '<option value="">Gagal memuat — coba lagi</option>';
                vehicleSelect.disabled = true;
            })
            .finally(() => vehicleLoading.classList.add('hidden'));
    }

    // ── Debounce untuk input durasi ───────────────────────────
    let debounceTimer;
    function debounced() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadAvailability, 400);
    }

    departureInput.addEventListener('change', loadAvailability);
    durationInput.addEventListener('input', debounced);
    durationInput.addEventListener('change', loadAvailability);

    // ── Jika old() value ada (setelah validation fail) ────────
    if (departureInput.value && durationInput.value) {
        loadAvailability();
    }

});
</script>
@endpush