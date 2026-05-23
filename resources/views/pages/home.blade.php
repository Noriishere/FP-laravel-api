@extends('layouts.guest')

@section('content')
    <div class="relative min-h-screen overflow-hidden bg-[#F9F7F4]">

        {{-- Background --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 h-[400px] w-[400px] rounded-full bg-primary/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-[300px] w-[300px] rounded-full bg-orange-400/10 blur-3xl"></div>
        </div>

        {{-- HERO --}}
        <section class="relative overflow-hidden bg-dark py-24">

            <div class="absolute inset-0 pointer-events-none">
                <div
                    class="absolute left-1/2 top-1/2 h-[700px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-3xl">
                </div>
            </div>

            <div
                class="relative mx-auto flex max-w-7xl flex-col items-start justify-between gap-10 px-6 lg:flex-row lg:items-center lg:px-12">

                <div class="max-w-2xl">

                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white/70">
                        <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                        Shuttle System Administration
                    </div>

                    <h1 class="font-fraunces mt-8 text-5xl font-black leading-tight text-white lg:text-6xl">
                        Centralized Shuttle
                        Management Platform
                    </h1>

                    <p class="mt-8 max-w-xl text-base leading-8 text-white/60">
                        Kelola jadwal, kendaraan, driver, booking,
                        dan monitoring perjalanan shuttle dalam satu dashboard modern.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">

                        <a href="/login"
                            class="inline-flex items-center gap-2 rounded-2xl bg-primary px-7 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-primaryDark">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Login Dashboard
                        </a>

                        <a href="#modules"
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-7 py-3 text-sm font-semibold text-white/70 transition hover:bg-white/10">
                            <i class="fa-solid fa-grid-2"></i>
                            Explore Modules
                        </a>

                    </div>

                </div>

                {{-- Stats --}}
                <div class="grid w-full max-w-lg grid-cols-2 gap-5">

                    @php
                        $stats = [
                            ['title' => 'Total Bookings', 'value' => '1,284', 'desc' => '+8% from last week'],
                            ['title' => 'Active Schedules', 'value' => '42', 'desc' => 'Operational today'],
                            ['title' => 'Drivers On Duty', 'value' => '18', 'desc' => 'Verified & active'],
                            ['title' => 'Fleet Availability', 'value' => '76%', 'desc' => 'Vehicles ready'],
                        ];
                    @endphp

                    @foreach ($stats as $stat)
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">

                            <p class="text-sm text-white/50">
                                {{ $stat['title'] }}
                            </p>

                            <h3 class="font-fraunces mt-3 text-4xl font-black text-white">
                                {{ $stat['value'] }}
                            </h3>

                            <p class="mt-2 text-xs text-green-400">
                                {{ $stat['desc'] }}
                            </p>

                        </div>
                    @endforeach

                </div>

            </div>

        </section>

        {{-- MODULES --}}
        <section id="modules" class="relative px-6 py-24 lg:px-12">

            <div class="mx-auto max-w-7xl">

                <div class="section-label">
                    System Modules
                </div>

                <h2 class="font-fraunces text-4xl font-black text-dark">
                    Complete operational control
                </h2>

                <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @php
                        $modules = [
                            [
                                'icon' => 'fa-route',
                                'color' => 'blue',
                                'title' => 'Schedule Management',
                                'desc' => 'Configure routes, departure times, pricing, and availability.',
                            ],
                            [
                                'icon' => 'fa-bus',
                                'color' => 'orange',
                                'title' => 'Vehicle Management',
                                'desc' => 'Maintain fleet data, capacity, and operational status.',
                            ],
                            [
                                'icon' => 'fa-user-tie',
                                'color' => 'purple',
                                'title' => 'Driver Management',
                                'desc' => 'Monitor driver verification, assignments, and performance.',
                            ],
                            [
                                'icon' => 'fa-ticket',
                                'color' => 'green',
                                'title' => 'Booking Control',
                                'desc' => 'View, validate, and manage all customer reservations.',
                            ],
                            [
                                'icon' => 'fa-location-dot',
                                'color' => 'red',
                                'title' => 'Live Tracking',
                                'desc' => 'Track vehicle locations and trip progress in real-time.',
                            ],
                            [
                                'icon' => 'fa-chart-line',
                                'color' => 'indigo',
                                'title' => 'Reporting & Analytics',
                                'desc' => 'Generate operational insights and performance reports.',
                            ],
                        ];
                    @endphp

                    @foreach ($modules as $module)
                        <div
                            class="group rounded-[28px] border border-borderColor bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $module['color'] }}-50">

                                <i class="fa-solid {{ $module['icon'] }} text-xl text-{{ $module['color'] }}-500"></i>

                            </div>

                            <h3 class="mt-6 font-fraunces text-2xl font-bold text-dark">
                                {{ $module['title'] }}
                            </h3>

                            <p class="mt-4 text-sm leading-7 text-grayText">
                                {{ $module['desc'] }}
                            </p>

                        </div>
                    @endforeach

                </div>

            </div>

        </section>

        {{-- ACTIVITY --}}
        <section class="px-6 pb-24 lg:px-12">

            <div class="mx-auto max-w-7xl">

                <div class="section-label">
                    Live Activity
                </div>

                <h2 class="font-fraunces text-4xl font-black text-dark">
                    Recent operational updates
                </h2>

                <div class="mt-12 overflow-hidden rounded-[32px] border border-borderColor bg-white shadow-sm">

                    @php
                        $activities = [
                            ['text' => 'New booking created', 'time' => '2 minutes ago'],
                            ['text' => 'Driver assigned to schedule', 'time' => '10 minutes ago'],
                            ['text' => 'Vehicle status updated', 'time' => '30 minutes ago'],
                            ['text' => 'Schedule created', 'time' => '1 hour ago'],
                        ];
                    @endphp

                    <div class="divide-y divide-borderColor">

                        @foreach ($activities as $activity)
                            <div class="flex items-center justify-between px-8 py-5 transition hover:bg-bg">

                                <div class="flex items-center gap-4">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10">
                                        <i class="fa-solid fa-bolt text-xs text-primary"></i>
                                    </div>

                                    <p class="text-sm font-medium text-dark">
                                        {{ $activity['text'] }}
                                    </p>

                                </div>

                                <span class="text-xs text-grayText">
                                    {{ $activity['time'] }}
                                </span>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </section>

    </div>
@endsection
