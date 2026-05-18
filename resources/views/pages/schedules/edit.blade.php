@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <div class="flex items-center gap-3">
        <a href="{{ route('schedules.index') }}"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 shadow-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div>
            <h1 class="text-xl font-semibold text-gray-800">
                Edit Jadwal
            </h1>

            <p class="text-sm text-gray-500 mt-0.5">
                Perbarui detail jadwal perjalanan
            </p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <p class="text-sm font-medium text-red-700 mb-1">
            Terdapat kesalahan:
        </p>

        <ul class="text-sm text-red-600 space-y-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

        <form method="POST" action="{{ route('schedules.update', $schedule->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ROUTE --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Route
                    </label>

                    <select
                        name="route_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white @error('route_id') border-red-400 @enderror"
                        required>

                        <option value="">— Pilih Route —</option>

                        @foreach ($routes as $route)

                            @php
                                $sortedStops = $route->stops->sortBy('order');
                                $origin = $sortedStops->first();
                                $destination = $sortedStops->last();
                            @endphp

                            <option
                                value="{{ $route->id }}"
                                {{
                                    old('route_id', $schedule->route_id) == $route->id
                                    ? 'selected'
                                    : ''
                                }}>
                                {{ $origin?->name ?? '-' }}
                                →
                                {{ $destination?->name ?? '-' }}
                                ({{ $route->distance }} km)
                            </option>

                        @endforeach

                    </select>

                    @error('route_id')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('route')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- DEPARTURE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal & Waktu Keberangkatan
                    </label>

                    <input
                        type="datetime-local"
                        id="departure_time"
                        name="departure_time"
                        value="{{ old('departure_time', \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d\TH:i')) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('departure_time') border-red-400 @enderror"
                        required>

                    @error('departure_time')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- DURATION --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Durasi (menit)
                    </label>

                    <div class="relative">
                        <input
                            type="number"
                            id="duration"
                            name="duration"
                            min="1"
                            value="{{ old('duration', $schedule->estimated_duration) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 pr-16 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('duration') border-red-400 @enderror"
                            required>

                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                            menit
                        </span>
                    </div>

                    <p id="arrivalPreview"
                        class="mt-1 text-xs text-gray-400 hidden">
                        Estimasi tiba:
                        <span id="arrivalTime"
                            class="font-medium text-gray-600"></span>
                    </p>

                    @error('duration')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- DRIVER --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Driver
                    </label>

                    <div class="relative">

                        <select
                            name="driver_id"
                            id="driver_select"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('driver_id') border-red-400 @enderror"
                            required>

                            <option value="{{ $schedule->driver_id }}">
                                {{ $schedule->driver->user->name }}
                            </option>

                        </select>

                        <div id="driverLoading"
                            class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin w-4 h-4 text-primary"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"/>
                                <path class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>

                    </div>

                    @error('driver')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- VEHICLE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kendaraan
                    </label>

                    <div class="relative">

                        <select
                            name="vehicle_id"
                            id="vehicle_select"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('vehicle_id') border-red-400 @enderror"
                            required>

                            <option value="{{ $schedule->vehicle_id }}">
                                {{ $schedule->vehicle->name }}
                                ({{ $schedule->vehicle->capacity }} seat)
                            </option>

                        </select>

                        <div id="vehicleLoading"
                            class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin w-4 h-4 text-primary"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"/>
                                <path class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>

                    </div>

                    @error('vehicle')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PRICE --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Harga
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="price"
                            min="0"
                            step="1000"
                            value="{{ old('price', $schedule->price) }}"
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('price') border-red-400 @enderror"
                            required>
                    </div>

                    @error('price')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary/90 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"/>
                    </svg>

                    Update Jadwal
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

    const arrivalPreview = document.getElementById('arrivalPreview');
    const arrivalTimeEl  = document.getElementById('arrivalTime');

    function updateArrivalPreview() {

        const departure = departureInput.value;
        const duration  = parseInt(durationInput.value);

        if (!departure || !duration || duration <= 0) {
            arrivalPreview.classList.add('hidden');
            return;
        }

        const arrivalDate =
            new Date(
                new Date(departure).getTime()
                + duration * 60000
            );

        arrivalTimeEl.textContent =
            arrivalDate.toLocaleString('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

        arrivalPreview.classList.remove('hidden');
    }

    departureInput.addEventListener('change', updateArrivalPreview);
    durationInput.addEventListener('input', updateArrivalPreview);

    updateArrivalPreview();

});
</script>
@endpush