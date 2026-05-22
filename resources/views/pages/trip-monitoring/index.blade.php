@extends('layouts.app')

@section('content')

<div class="space-y-5">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Trip Monitoring
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Monitor realtime ongoing shuttle trips
            </p>

        </div>

        <div class="text-sm text-gray-400">
            Auto refresh every 5 seconds
        </div>

    </div>

    {{-- STATS --}}
    <div
        id="stats-container"
        class="grid grid-cols-1 md:grid-cols-4 gap-4"
    >
    </div>

    {{-- TRIPS --}}
    <div
        id="trip-container"
        class="grid grid-cols-1 lg:grid-cols-2 gap-5"
    >
    </div>

</div>

<script>

    async function loadTrips() {

        try {

            const response = await fetch(
                "{{ route('trip-monitoring.data') }}"
            );

            const result = await response.json();

            renderStats(result.data);

            renderTrips(result.data);

        } catch (error) {

            console.error(error);
        }
    }

    function renderStats(trips) {

        const statsContainer = document.getElementById(
            'stats-container'
        );

        const totalTrips = trips.length;

        const liveTrips = trips.filter(
            item => item.tracking_status === 'live'
        ).length;

        const offlineTrips = trips.filter(
            item => item.tracking_status === 'offline'
        ).length;

        const activeDrivers = new Set(
            trips.map(item => item.driver?.name)
        ).size;

        statsContainer.innerHTML = `

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Ongoing Trips
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    ${totalTrips}
                </h2>

            </div>

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Live Tracking
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    ${liveTrips}
                </h2>

            </div>

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Offline Tracking
                </p>

                <h2 class="text-3xl font-bold text-red-500 mt-2">
                    ${offlineTrips}
                </h2>

            </div>

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Active Drivers
                </p>

                <h2 class="text-3xl font-bold text-yellow-500 mt-2">
                    ${activeDrivers}
                </h2>

            </div>
        `;
    }

    function renderTrips(trips) {

        const container = document.getElementById(
            'trip-container'
        );

        if (! trips.length) {

            container.innerHTML = `

                <div class="col-span-full">

                    <div class="bg-white rounded-2xl shadow p-16 text-center">

                        <div class="w-20 h-20 rounded-full bg-gray-100 mx-auto flex items-center justify-center mb-4">

                            <i class="fa-solid fa-bus text-3xl text-gray-400"></i>

                        </div>

                        <h3 class="text-lg font-semibold text-gray-700">
                            No Ongoing Trips
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            There are currently no active shuttle trips
                        </p>

                    </div>

                </div>
            `;

            return;
        }

        container.innerHTML = trips.map(trip => `

            <div class="bg-white rounded-2xl shadow overflow-hidden">

                {{-- TOP --}}
                <div class="p-5 border-b">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h3 class="font-bold text-lg text-gray-800">

                                ${trip.route?.name ?? '-'}

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                ${trip.route?.origin?.name ?? '-'}

                                →

                                ${trip.route?.destination?.name ?? '-'}

                            </p>

                        </div>

                        <span class="
                            ${trip.tracking_status === 'live'
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600'}
                            text-xs px-3 py-1 rounded-full font-medium
                        ">

                            ${trip.tracking_status.toUpperCase()}

                        </span>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="p-5 space-y-4">

                    {{-- DRIVER --}}
                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">

                            ${trip.driver?.name?.charAt(0).toUpperCase() ?? '?'}

                        </div>

                        <div>

                            <p class="font-medium text-gray-800">

                                ${trip.driver?.name ?? '-'}

                            </p>

                            <p class="text-sm text-gray-500">
                                Driver
                            </p>

                        </div>

                    </div>

                    {{-- VEHICLE --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div class="bg-gray-50 rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Vehicle
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.vehicle?.name ?? '-'}

                            </h4>

                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Plate Number
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.vehicle?.plate_number ?? '-'}

                            </h4>

                        </div>

                    </div>

                    {{-- GPS --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div class="border rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Latitude
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.latitude}

                            </h4>

                        </div>

                        <div class="border rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Longitude
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.longitude}

                            </h4>

                        </div>

                    </div>

                    {{-- SPEED --}}
                    <div class="grid grid-cols-3 gap-4">

                        <div class="border rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Speed
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.speed ?? 0} km/h

                            </h4>

                        </div>

                        <div class="border rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Heading
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.heading ?? 0}

                            </h4>

                        </div>

                        <div class="border rounded-xl p-4">

                            <p class="text-xs text-gray-500 mb-1">
                                Accuracy
                            </p>

                            <h4 class="font-semibold text-gray-800">

                                ${trip.accuracy ?? 0} m

                            </h4>

                        </div>

                    </div>

                    {{-- TIME --}}
                    <div class="border rounded-xl p-4">

                        <p class="text-xs text-gray-500 mb-1">
                            Last Update
                        </p>

                        <h4 class="font-semibold text-gray-800">

                            ${trip.recorded_at}

                        </h4>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-4 bg-gray-50 border-t">

                    <div class="flex items-center justify-between">

                        <div class="text-sm text-gray-500">

                            Schedule ID:
                            #${trip.schedule_id}

                        </div>

                        <a
                            href="/admin/maps/tracking/${trip.schedule_id}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition"
                        >

                            <i class="fa-solid fa-location-dot mr-1"></i>

                            Live Tracking

                        </a>

                    </div>

                </div>

            </div>

        `).join('');
    }

    loadTrips();

    setInterval(() => {

        loadTrips();

    }, 5000);

</script>

@endsection