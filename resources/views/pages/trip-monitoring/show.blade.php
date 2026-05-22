@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Trip Monitoring Detail
                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    {{ $schedule->route?->origin?->name }}

                    →

                    {{ $schedule->route?->destination?->name }}

                </p>

            </div>

            <a href="{{ route('trip-monitoring.index') }}"
                class="bg-gray-800 text-white px-5 py-3 rounded-xl text-sm hover:bg-black transition">

                <i class="fa-solid fa-arrow-left mr-2"></i>

                Back

            </a>

        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- DRIVER --}}
                <div class="bg-white rounded-2xl shadow p-5">

                    <div class="flex items-center gap-4">

                        <div
                            class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">

                            {{ strtoupper(substr($schedule->driver?->user?->name, 0, 1)) }}

                        </div>

                        <div>

                            <h3 class="font-bold text-lg text-gray-800">

                                {{ $schedule->driver?->user?->name }}

                            </h3>

                            <p class="text-sm text-gray-500">
                                Driver
                            </p>

                        </div>

                    </div>

                </div>

                {{-- VEHICLE --}}
                <div class="bg-white rounded-2xl shadow p-5 space-y-4">

                    <div>

                        <p class="text-xs text-gray-500 mb-1">
                            Vehicle
                        </p>

                        <h3 class="font-semibold text-gray-800">

                            {{ $schedule->vehicle?->name }}

                        </h3>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500 mb-1">
                            Plate Number
                        </p>

                        <h3 class="font-semibold text-gray-800">

                            {{ $schedule->vehicle?->plate_number }}

                        </h3>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500 mb-1">
                            Status
                        </p>

                        <span
                            class="
                        text-xs px-3 py-1 rounded-full font-medium

                        {{ $schedule->status == 'completed'
                            ? 'bg-green-100 text-green-600'
                            : ($schedule->status == 'on-going'
                                ? 'bg-blue-100 text-blue-600'
                                : 'bg-yellow-100 text-yellow-600') }}
                    ">

                            {{ strtoupper($schedule->status) }}

                        </span>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-2">

                <x-trip-tracking-map :scheduleId="$schedule->id" />

            </div>

        </div>

    </div>
@endsection
