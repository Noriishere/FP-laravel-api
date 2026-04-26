@extends('layouts.app')
@section('content')

<div class="bg-white rounded-xl shadow p-5">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Vehicles</h3>

        <a href="{{ route('vehicles.create') }}" 
           class="bg-primary text-white px-3 py-2 rounded-lg text-sm">
            + Tambah Vehicle
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-600 p-2 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500">
                    <th>Nama</th>
                    <th>Plat</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($vehicles as $v)
                <tr class="border-b">
                    <td>{{ $v->name }}</td>
                    <td>{{ $v->plate_number }}</td>
                    <td>{{ $v->capacity }} seat</td>

                    <td class="flex gap-3 py-2">

                        <a href="{{ route('vehicles.edit', $v->id) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form action="{{ route('vehicles.destroy', $v->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Yakin?')" 
                                class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>

</div>

@endsection