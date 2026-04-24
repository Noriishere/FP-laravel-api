@extends('layouts.app')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Users</p>
        <h2 class="text-2xl font-bold mt-2">120</h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Bookings</p>
        <h2 class="text-2xl font-bold mt-2">87</h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-sm text-gray-500">Active Schedules</p>
        <h2 class="text-2xl font-bold mt-2">14</h2>
    </div>

</div>

@endsection