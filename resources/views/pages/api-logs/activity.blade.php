@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Total Requests
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    {{ \App\Models\ApiActivityLog::count() }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Success
                </p>

                <h2 class="text-2xl font-bold mt-2 text-green-600">
                    {{ \App\Models\ApiActivityLog::where('status_code', '<', 400)->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Client Error
                </p>

                <h2 class="text-2xl font-bold mt-2 text-yellow-500">
                    {{ \App\Models\ApiActivityLog::whereBetween('status_code', [400, 499])->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Server Error
                </p>

                <h2 class="text-2xl font-bold mt-2 text-red-600">
                    {{ \App\Models\ApiActivityLog::where('status_code', '>=', 500)->count() }}
                </h2>
            </div>

        </div>

        {{-- MAIN --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            {{-- HEADER --}}
            <div class="p-5 border-b">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            API Activity Logs
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Semua aktivitas request API
                        </p>
                    </div>

                    <a href="{{ route('api-logs.crashes') }}"
                        class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-700 transition">

                        Crash Logs

                    </a>

                </div>

            </div>

            {{-- FILTER --}}
            <div class="p-5 border-b">

                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari URL atau method..." class="border rounded-xl px-4 py-2.5 text-sm">

                    <select name="status" class="border rounded-xl px-4 py-2.5 text-sm">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="200">
                            200
                        </option>

                        <option value="401">
                            401
                        </option>

                        <option value="404">
                            404
                        </option>

                        <option value="500">
                            500
                        </option>

                    </select>

                    <button class="bg-gray-900 text-white rounded-xl px-4 py-2.5 text-sm">

                        Filter

                    </button>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[1000px]">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-sm text-gray-500">

                            <th class="px-6 py-4">
                                Method
                            </th>

                            <th class="px-6 py-4">
                                URL
                            </th>

                            <th class="px-6 py-4">
                                Status
                            </th>

                            <th class="px-6 py-4">
                                Duration
                            </th>

                            <th class="px-6 py-4">
                                User
                            </th>

                            <th class="px-6 py-4">
                                Time
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($logs as $log)
                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="px-6 py-4 font-semibold">
                                    {{ $log->method }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $log->url }}
                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="
                                    text-xs font-medium px-3 py-1 rounded-full

                                    {{ $log->status_code < 400
                                        ? 'bg-green-100 text-green-600'
                                        : ($log->status_code < 500
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-red-100 text-red-600') }}
                                ">

                                        {{ $log->status_code }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ $log->duration_ms }} ms
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ $log->user?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-16 text-gray-500">

                                    Tidak ada activity logs

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
