@extends('layouts.app')
@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h3 class="text-sm font-semibold mb-4">Edit Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}" class="space-y-3">
        @csrf
        @method('PUT')

        <input name="name" value="{{ $vehicle->name }}" class="w-full border p-2 rounded">
        <input name="plate_number" value="{{ $vehicle->plate_number }}" class="w-full border p-2 rounded">
        <input name="capacity" type="number" value="{{ $vehicle->capacity }}" class="w-full border p-2 rounded">

        <button class="bg-primary text-white px-4 py-2 rounded">
            Update
        </button>
    </form>

</div>

@endsection