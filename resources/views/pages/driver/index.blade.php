@extends('layouts.app')
@section('content')

<div class="bg-white rounded-xl shadow p-5">

    <h3 class="text-sm font-semibold text-gray-700 mb-4">
        Verifikasi Driver
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500">
                    <th>Driver</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($documents as $doc)
                <tr class="border-b">
                    <td>{{ $doc->driver->user->name }}</td>
                    <td class="uppercase">{{ $doc->type }}</td>

                    <td>
                        <span class="text-xs px-2 py-1 rounded
                        {{ $doc->status=='pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
                        {{ $doc->status=='approved' ? 'bg-green-100 text-green-600' : '' }}
                        {{ $doc->status=='rejected' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ $doc->status }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('driver-documents.show', $doc->id) }}"
                           class="text-blue-600">
                           Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-400 py-4">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>

</div>

@endsection