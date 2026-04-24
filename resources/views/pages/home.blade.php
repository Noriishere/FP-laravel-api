@extends('layouts.guest')

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- HEADER -->
    <section class="bg-[#C00707] text-white py-16">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-semibold">
                    Shuttle System Administration
                </h1>
                <p class="text-red-100 mt-2 text-sm">
                    Centralized management for schedules, vehicles, drivers, and bookings
                </p>
            </div>

            <div>
                <a href="/login" class="bg-white text-[#C00707] px-5 py-2 rounded-md text-sm font-medium shadow">
                    Login
                </a>
            </div>
        </div>
    </section>

    <!-- SUMMARY -->
    <section class="max-w-7xl mx-auto px-6 -mt-10">
        <div class="grid md:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <p class="text-sm text-gray-500">Total Bookings</p>
                <h3 class="text-2xl font-semibold mt-2 text-gray-800">1,284</h3>
                <p class="text-xs text-green-600 mt-1">+8% from last week</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <p class="text-sm text-gray-500">Active Schedules</p>
                <h3 class="text-2xl font-semibold mt-2 text-gray-800">42</h3>
                <p class="text-xs text-gray-400 mt-1">Operational today</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <p class="text-sm text-gray-500">Drivers On Duty</p>
                <h3 class="text-2xl font-semibold mt-2 text-gray-800">18</h3>
                <p class="text-xs text-gray-400 mt-1">Verified & active</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <p class="text-sm text-gray-500">Fleet Availability</p>
                <h3 class="text-2xl font-semibold mt-2 text-gray-800">76%</h3>
                <p class="text-xs text-gray-400 mt-1">Vehicles ready</p>
            </div>

        </div>
    </section>

    <!-- MODULES -->
    <section class="max-w-7xl mx-auto px-6 mt-16">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            System Modules
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Schedule Management</h3>
                <p class="text-sm text-gray-600">
                    Configure routes, departure times, pricing, and availability.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Vehicle Management</h3>
                <p class="text-sm text-gray-600">
                    Maintain fleet data, capacity, and operational status.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Driver Management</h3>
                <p class="text-sm text-gray-600">
                    Monitor driver verification, assignments, and performance.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Booking Control</h3>
                <p class="text-sm text-gray-600">
                    View, validate, and manage all customer reservations.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Live Tracking</h3>
                <p class="text-sm text-gray-600">
                    Track vehicle locations and trip progress in real-time.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow transition">
                <h3 class="font-semibold text-gray-800 mb-2">Reporting & Analytics</h3>
                <p class="text-sm text-gray-600">
                    Generate operational insights and performance reports.
                </p>
            </div>

        </div>
    </section>

    <!-- ACTIVITY -->
    <section class="max-w-7xl mx-auto px-6 mt-16 pb-20">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            Recent Activity
        </h2>

        <div class="bg-white rounded-lg border border-gray-200 shadow">
            <div class="divide-y">

                <div class="p-4 flex justify-between text-sm">
                    <span class="text-gray-600">New booking created</span>
                    <span class="text-gray-400">2 minutes ago</span>
                </div>

                <div class="p-4 flex justify-between text-sm">
                    <span class="text-gray-600">Driver assigned to schedule</span>
                    <span class="text-gray-400">10 minutes ago</span>
                </div>

                <div class="p-4 flex justify-between text-sm">
                    <span class="text-gray-600">Vehicle status updated</span>
                    <span class="text-gray-400">30 minutes ago</span>
                </div>

                <div class="p-4 flex justify-between text-sm">
                    <span class="text-gray-600">Schedule created</span>
                    <span class="text-gray-400">1 hour ago</span>
                </div>

            </div>
        </div>
    </section>

</div>

@endsection