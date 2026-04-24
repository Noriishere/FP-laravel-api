@extends('layouts.app')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500">Total Pengguna</p>
            <h2 class="text-2xl font-semibold mt-1">120</h2>
            <p class="text-xs text-green-600 mt-1">+8 bulan ini</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500">Total Pemesanan</p>
            <h2 class="text-2xl font-semibold mt-1">87</h2>
            <p class="text-xs text-green-600 mt-1">+12 minggu ini</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500">Jadwal Aktif</p>
            <h2 class="text-2xl font-semibold mt-1">14</h2>
            <p class="text-xs text-gray-400 mt-1">3 rute berjalan</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500">Kendaraan Siap Berangkat</p>
            <h2 class="text-2xl font-semibold mt-1">9</h2>
            <p class="text-xs text-yellow-600 mt-1">2 dalam servis</p>
        </div>
    </div>

    {{-- Tabel / Konten Tambahan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Pemesanan Terbaru --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Pemesanan Terbaru</h3>
            {{-- Isi tabel nanti di sini --}}
        </div>

        {{-- Status Armada --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Status Kendaraan</h3>
            {{-- Isi tabel nanti di sini --}}
        </div>

    </div>
@endsection
