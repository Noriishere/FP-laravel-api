@extends('layouts.app')
@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h3 class="text-sm font-semibold mb-4">Edit Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- NAME --}}
        <input name="name" value="{{ old('name', $vehicle->name) }}"
            class="w-full border p-2 rounded">

        {{-- PLATE NUMBER SPLIT --}}
        <div>
            <label class="text-sm text-gray-600">Plat Nomor</label>

            <div class="flex gap-2 mt-1">

                {{-- PREFIX --}}
                <select name="plate_prefix"
                    class="border rounded px-3 py-2 text-sm">
                    @foreach(['B','D','L','F','AB'] as $code)
                        <option value="{{ $code }}"
                            {{ old('plate_prefix', $vehicle->plate_prefix) == $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>

                {{-- ANGKA --}}
                <input name="plate_number_main"
                    value="{{ old('plate_number_main', $vehicle->plate_number_main) }}"
                    class="w-24 border rounded px-3 py-2 text-sm text-center">

                {{-- SUFFIX --}}
                <input name="plate_suffix"
                    value="{{ old('plate_suffix', $vehicle->plate_suffix) }}"
                    oninput="this.value = this.value.toUpperCase()"
                    class="w-24 border rounded px-3 py-2 text-sm text-center uppercase">

            </div>
        </div>

        {{-- CAPACITY --}}
        <input name="capacity" type="number"
            value="{{ old('capacity', $vehicle->capacity) }}"
            class="w-full border p-2 rounded">

        {{-- SUBMIT --}}
        <button class="bg-primary text-white px-4 py-2 rounded">
            Update
        </button>

    </form>

</div>

@endsection