@extends('layouts.app')
@section('content')

<div class="bg-white rounded-2xl shadow-sm border p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-base font-semibold text-gray-800">
                Vehicles
            </h3>
            <p class="text-xs text-gray-400">
                Kelola data kendaraan
            </p>
        </div>

        <a href="{{ route('vehicles.create') }}" 
           class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
            + Tambah Vehicle
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-600 p-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-3">Nama Kendaraan</th>
                    <th>Plat Nomor</th>
                    <th>Kapasitas</th>
                    <th>Tipe</th>
                    <th>Warna</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse ($vehicles as $v)
                <tr class="hover:bg-gray-50 transition">

                    {{-- NAME --}}
                    <td class="py-3 font-medium text-gray-800">
                        {{ $v->name }}
                    </td>

                    {{-- PLATE --}}
                    <td>
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium">
                            {{ $v->plate_number }}
                        </span>
                    </td>

                    {{-- CAPACITY --}}
                    <td>
                        <span class="text-gray-600">
                            {{ $v->capacity }} seat
                        </span>
                    </td>
                    {{-- TYPE --}}
                    <td>
                        <span class="text-gray-600">
                            {{ $v->type }}
                        </span>
                    </td>
                    {{-- COLOR --}}
                    <td>
                        <span class="text-gray-600">
                            {{ $v->color }}
                        </span>
                    </td>
                    {{-- ACTION --}}
                    <td>
                        <div class="flex justify-center gap-3">

                            <a href="{{ route('vehicles.edit', $v->id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <form action="{{ route('vehicles.destroy', $v->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Yakin hapus vehicle ini?')" 
                                    class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-400 text-sm">
                        Belum ada data kendaraan
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $vehicles->links() }}
    </div>

</div>

@endsection