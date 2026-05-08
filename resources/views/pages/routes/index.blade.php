@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Manajemen Route</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola semua rute perjalanan yang tersedia</p>
            </div>
            <a href="{{ route('routes.create') }}"
                class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-primary/90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Route
            </a>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3 w-10">#
                        </th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Origin
                        </th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">
                            Destination</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">
                            Koordinat Origin</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">
                            Koordinat Dest.</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Jarak
                        </th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($routes as $i => $route)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-5 py-3.5 text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                                    <span class="font-medium text-gray-800 truncate max-w-[180px]"
                                        title="{{ $route->origin?->name }}">
                                        {{ $route->origin?->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                                    <span class="font-medium text-gray-800 truncate max-w-[180px]"
                                        title="{{ $route->destination?->name }}">
                                        {{ $route->destination?->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">
                                {{ number_format($route->origin?->lat, 4) }}, {{ number_format($route->origin?->lng, 4) }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">
                                {{ number_format($route->destination?->lat, 4) }},
                                {{ number_format($route->destination?->lng, 4) }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    {{ $route->distance }} km
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('routes.show', $route->id) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                                    Detail
                                </a>
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('routes.edit', $route->id) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-primary bg-gray-100 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('routes.destroy', $route->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus route ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada route yang dibuat.</p>
                                    <a href="{{ route('admin.routes.create') }}"
                                        class="text-primary text-sm font-medium hover:underline">Buat route pertama →</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
