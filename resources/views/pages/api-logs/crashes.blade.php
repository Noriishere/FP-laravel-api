@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Total Crash Logs
                </p>

                <h2 class="text-2xl font-bold mt-2 text-red-600">
                    {{ \App\Models\ApiCrashLog::count() }}
                </h2>

            </div>

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Hari Ini
                </p>

                <h2 class="text-2xl font-bold mt-2 text-orange-500">
                    {{ \App\Models\ApiCrashLog::whereDate('created_at', today())->count() }}
                </h2>

            </div>

            <div class="bg-white rounded-2xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Server Error 500
                </p>

                <h2 class="text-2xl font-bold mt-2 text-gray-800">
                    {{ \App\Models\ApiCrashLog::where('status_code', 500)->count() }}
                </h2>

            </div>

        </div>

        {{-- MAIN CARD --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            {{-- HEADER --}}
            <div class="p-5 border-b">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            API Crash Logs
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Semua error dan exception API
                        </p>

                    </div>

                    <a href="{{ route('api-logs.activity') }}"
                        class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-black transition">

                        Activity Logs

                    </a>

                </div>

            </div>

            {{-- FILTER --}}
            <div class="p-5 border-b">

                <form method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari message atau URL..."
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm">

                        <button class="bg-red-600 text-white rounded-xl px-4 py-2.5 text-sm hover:bg-red-700 transition">

                            Filter

                        </button>

                    </div>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[1100px]">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-sm text-gray-500">

                            <th class="px-6 py-4">
                                Status
                            </th>

                            <th class="px-6 py-4">
                                Method
                            </th>

                            <th class="px-6 py-4">
                                URL
                            </th>

                            <th class="px-6 py-4">
                                Message
                            </th>

                            <th class="px-6 py-4">
                                User
                            </th>

                            <th class="px-6 py-4">
                                Time
                            </th>

                            <th class="px-6 py-4 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($logs as $log)
                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="px-6 py-4">

                                    <span
                                        class="
                                    text-xs font-medium px-3 py-1 rounded-full
                                    bg-red-100 text-red-600
                                ">

                                        {{ $log->status_code }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ $log->method }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $log->url }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 max-w-md truncate">
                                    {{ $log->message }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ $log->user?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-center">

                                        <a href="{{ route('api-logs.crashes.show', $log->id) }}"
                                            class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">

                                            <i class="fa-solid fa-eye"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-16 text-gray-500">

                                    Tidak ada crash logs

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="p-5 border-t">
                {{ $logs->links() }}
            </div>

        </div>

    </div>
@endsection
