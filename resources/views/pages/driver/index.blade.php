@extends('layouts.app')
@section('content')

<div class="bg-white rounded-2xl shadow-sm border p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-5">
        <h3 class="text-base font-semibold text-gray-800">
            Verifikasi Driver
        </h3>

        {{-- OPTIONAL FILTER / SEARCH (future ready) --}}
        <div class="text-xs text-gray-400">
            Total: {{ $documents->total() }}
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-3">Driver</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($documents as $doc)
                <tr class="hover:bg-gray-50 transition">

                    {{-- DRIVER --}}
                    <td class="py-3">
                        <div class="font-medium text-gray-800">
                            {{ $doc->driver->user->name }}
                        </div>
                        <div class="text-xs text-gray-400">
                            ID: {{ $doc->driver_id }}
                        </div>
                    </td>

                    {{-- TYPE --}}
                    <td>
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">
                            {{ $doc->type }}
                        </span>
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $doc->status=='pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
                            {{ $doc->status=='approved' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $doc->status=='rejected' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </td>

                    {{-- ACTION --}}
                    <td>
                        <div class="flex justify-center">
                            <a href="{{ route('driver-documents.show', $doc->id) }}"
                               class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fa-solid fa-eye"></i>
                                Detail
                            </a>
                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-400 text-sm">
                        Tidak ada dokumen
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $documents->links() }}
    </div>

</div>

@endsection