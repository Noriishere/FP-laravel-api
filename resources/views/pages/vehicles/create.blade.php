@extends('layouts.app')
@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h3 class="text-sm font-semibold mb-4">Tambah Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.store') }}" class="space-y-4">
    @csrf

    {{-- NAME --}}
    <input name="name" placeholder="Nama kendaraan"
        class="w-full border p-2 rounded">

    {{-- PLATE NUMBER SPLIT --}}
    <div>
        <label class="text-sm text-gray-600">Plat Nomor</label>

        <div class="flex gap-2 mt-1">

            {{-- KODE DAERAH --}}
            <select name="plate_prefix"
                class="border rounded px-3 py-2 text-sm">
                <option value="B">B (Jakarta)</option>
                <option value="D">D (Bandung)</option>
                <option value="L">L (Surabaya)</option>
                <option value="F">F (Bogor)</option>
                <option value="AB">AB (Jogja)</option>
            </select>

            {{-- ANGKA --}}
            <input name="plate_number_main"
                type="text"
                placeholder="1234"
                class="w-24 border rounded px-3 py-2 text-sm text-center">

            {{-- HURUF AKHIR --}}
            <input name="plate_suffix"
                type="text"
                placeholder="XYZ"
                class="w-24 border rounded px-3 py-2 text-sm text-center uppercase">
        </div>
    </div>

    {{-- CAPACITY --}}
    <input name="capacity" type="number" placeholder="Kapasitas"
        class="w-full border p-2 rounded">
    {{-- TYPE --}}
    <input name="type" type="text" placeholder="Tipe kendaraan (misal: hiace, elf, bus)"
        class="w-full border p-2 rounded">
    {{-- COLOR --}}
    <input name="color" type="text" placeholder="Warna kendaraan"
        class="w-full border p-2 rounded">
    <button class="bg-primary text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

</div>

@endsection