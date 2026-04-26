@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-5">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">
                Driver List
            </h3>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-2">Nama</th>
                        <th>Email</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($drivers as $driver)
                        <tr class="border-b">

                            {{-- NAME --}}
                            <td class="py-2 font-medium">
                                {{ $driver->user->name }}
                            </td>

                            {{-- EMAIL --}}
                            <td>
                                {{ $driver->user->email }}
                            </td>

                            {{-- DOCUMENT COUNT --}}
                            <td>
                                <span class="text-xs px-2 py-1 rounded bg-gray-100">
                                    {{ $driver->documents_count }} file
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span
                                    class="text-xs px-2 py-1 rounded
        {{ $driver->verification_status == 'approved' ? 'bg-green-100 text-green-600' : '' }}
        {{ $driver->verification_status == 'pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
        {{ $driver->verification_status == 'rejected' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ ucfirst($driver->verification_status) }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="py-2">
                                <div class="flex justify-center gap-3">

                                    <a href="{{ url('/admin/driver-documents?driver_id=' . $driver->id) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-file-lines"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400">
                                Tidak ada driver
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $drivers->links() }}
        </div>

    </div>
@endsection
