@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Trip Monitoring
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Monitor all shuttle trips for today
                </p>

            </div>

            <div class="bg-white rounded-2xl shadow px-5 py-4">

                <p class="text-xs text-gray-500">
                    Total Schedule Today
                </p>

                <h3 class="text-3xl font-bold text-blue-600 mt-1">

                    {{ $schedules->count() }}

                </h3>

            </div>

        </div>

        {{-- FILTER --}}
        <div class="bg-white rounded-2xl shadow p-5">

            <form method="GET">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- SEARCH --}}
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search route or driver..."
                        class="border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

                    {{-- STATUS --}}
                    <select name="status"
                        class="border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="on-going" {{ request('status') == 'on-going' ? 'selected' : '' }}>
                            On Going
                        </option>

                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                    </select>

                    {{-- BUTTON --}}
                    <button
                        class="bg-blue-600 text-white rounded-xl px-4 py-3 text-sm font-medium hover:bg-blue-700 transition">
                        Filter
                    </button>

                </div>

            </form>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[1000px]">

                    <thead class="bg-gray-50 border-b">

                        <tr class="text-left text-gray-500">

                            <th class="px-5 py-4">
                                Route
                            </th>

                            <th class="px-5 py-4">
                                Driver
                            </th>

                            <th class="px-5 py-4">
                                Vehicle
                            </th>

                            <th class="px-5 py-4">
                                Departure
                            </th>

                            <th class="px-5 py-4">
                                Arrival
                            </th>

                            <th class="px-5 py-4">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($schedules as $schedule)
                            <tr class="border-b hover:bg-gray-50 transition">

                                {{-- ROUTE --}}
                                <td class="px-5 py-4">

                                    <div>

                                        <h4 class="font-semibold text-gray-800">

                                            {{ $schedule->route?->name }}

                                        </h4>

                                        <p class="text-xs text-gray-500 mt-1">

                                            {{ $schedule->route?->origin?->name }}

                                            →

                                            {{ $schedule->route?->destination?->name }}

                                        </p>

                                    </div>

                                </td>

                                {{-- DRIVER --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">

                                            {{ strtoupper(substr($schedule->driver?->user?->name, 0, 1)) }}

                                        </div>

                                        <div>

                                            <p class="font-medium text-gray-800">

                                                {{ $schedule->driver?->user?->name }}

                                            </p>

                                            <p class="text-xs text-gray-500">
                                                Driver
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                {{-- VEHICLE --}}
                                <td class="px-5 py-4">

                                    <div>

                                        <p class="font-medium text-gray-800">

                                            {{ $schedule->vehicle?->name }}

                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">

                                            {{ $schedule->vehicle?->plate_number }}

                                        </p>

                                    </div>

                                </td>

                                {{-- DEPARTURE --}}
                                <td class="px-5 py-4">

                                    <div>

                                        <p class="font-medium text-gray-800">

                                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y') }}

                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">

                                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}

                                        </p>

                                    </div>

                                </td>

                                {{-- ARRIVAL --}}
                                <td class="px-5 py-4">

                                    <div>

                                        <p class="font-medium text-gray-800">

                                            {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('d M Y') }}

                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">

                                            {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}

                                        </p>

                                    </div>

                                </td>

                                {{-- STATUS --}}
                                <td class="px-5 py-4">

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

                                </td>

                                {{-- ACTION --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center">

                                        <a href="{{ route('trip-monitoring.show', $schedule->id) }}"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs hover:bg-blue-700 transition">

                                            <i class="fa-solid fa-location-dot mr-1"></i>

                                            Monitor

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-16">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">

                                            <i class="fa-solid fa-bus text-3xl text-gray-400"></i>

                                        </div>

                                        <h3 class="text-lg font-semibold text-gray-700">

                                            No Schedule Found

                                        </h3>

                                        <p class="text-sm text-gray-500 mt-2">

                                            There are no schedules available today

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
