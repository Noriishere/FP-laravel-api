@extends('layouts.guest')

@section('content')

<!-- HERO -->
<section class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white py-24">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
        
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6">
                Shuttle Booking Tanpa Ribet 🚐
            </h1>
            <p class="text-lg text-blue-100 mb-8">
                Booking kursi, kelola perjalanan, dan tracking real-time dalam satu sistem terintegrasi.
            </p>

            <div class="flex gap-4">
                <a href="/register" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow">
                    Mulai Sekarang
                </a>
                <a href="/login" class="border border-white px-6 py-3 rounded-lg">
                    Login
                </a>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="bg-white/10 backdrop-blur p-6 rounded-2xl shadow-lg">
                <p class="text-sm text-blue-100">Preview Sistem</p>
                <div class="mt-4 bg-white text-gray-800 p-4 rounded-xl">
                    <p class="font-semibold">Booking Hari Ini</p>
                    <p class="text-2xl font-bold">+32</p>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- FEATURES -->
<section class="py-20 max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-12">
        Kenapa Pilih Sistem Ini?
    </h2>

    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <div class="text-blue-600 text-3xl mb-4">⚡</div>
            <h3 class="font-bold text-lg mb-2">Booking Cepat</h3>
            <p class="text-gray-600">Pilih rute dan kursi hanya dalam beberapa detik.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <div class="text-blue-600 text-3xl mb-4">📍</div>
            <h3 class="font-bold text-lg mb-2">Tracking Real-time</h3>
            <p class="text-gray-600">Pantau posisi shuttle secara langsung.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <div class="text-blue-600 text-3xl mb-4">🛠️</div>
            <h3 class="font-bold text-lg mb-2">Manajemen Lengkap</h3>
            <p class="text-gray-600">Kelola driver, kendaraan, dan jadwal dengan mudah.</p>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-20 bg-gray-100">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-12">Cara Kerja</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="text-4xl mb-4">1️⃣</div>
                <p class="font-semibold">Pilih Jadwal</p>
            </div>
            <div>
                <div class="text-4xl mb-4">2️⃣</div>
                <p class="font-semibold">Booking Kursi</p>
            </div>
            <div>
                <div class="text-4xl mb-4">3️⃣</div>
                <p class="font-semibold">Tracking Perjalanan</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-24 bg-blue-600 text-white text-center">
    <h2 class="text-3xl md:text-4xl font-bold mb-6">
        Siap Mulai Perjalananmu?
    </h2>
    <p class="text-blue-100 mb-8">
        Gunakan shuttle system yang cepat, aman, dan terintegrasi.
    </p>
    <a href="/register" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold shadow">
        Daftar Sekarang
    </a>
</section>

@endsection