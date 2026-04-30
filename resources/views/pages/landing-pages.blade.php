@extends('layouts.guest')

@section('content')

<nav class="flex justify-between items-center px-8 py-4 shadow">
    <h1 class="text-2xl font-bold text-primary">GASSIN</h1>

    <div class="hidden md:flex gap-6">
        <a href="#fitur" class="hover:text-primary">Fitur</a>
        <a href="#cara" class="hover:text-primary">Cara Kerja</a>
        <a href="#download" class="hover:text-primary">Download</a>
    </div>

    <a href="#" class="bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
        Download App
    </a>
</nav>

<section class="grid md:grid-cols-2 items-center px-8 py-16 gap-10">
    <div>
        <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
            Shuttle Lebih <span class="text-primary">Cepat & Praktis</span>
        </h2>

        <p class="text-gray-600 mb-6">
            GASSIN adalah aplikasi booking shuttle modern yang memudahkan kamu memesan perjalanan,
            memilih kursi, dan tracking perjalanan langsung dari smartphone.
        </p>

        <div class="flex gap-4">
            <a href="#" class="bg-primary text-white px-6 py-3 rounded-lg flex items-center gap-2 hover:opacity-90">
                <i class="fa-brands fa-google-play"></i>
                Download di Play Store
            </a>

            <a href="#fitur" class="border border-primary text-primary px-6 py-3 rounded-lg hover:bg-red-50">
                Lihat Fitur
            </a>
        </div>
    </div>

    <div class="flex justify-center">
        <i class="fa-solid fa-bus text-primary text-[140px]"></i>
    </div>
</section>

<section id="fitur" class="bg-gray-50 py-16 px-8">
    <h3 class="text-3xl font-bold text-center mb-12">Fitur Utama</h3>

    <div class="grid md:grid-cols-3 gap-8">

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <i class="fa-solid fa-ticket text-primary text-3xl mb-4"></i>
            <h4 class="font-bold text-xl mb-2">Booking Instan</h4>
            <p class="text-gray-600">Pesan shuttle hanya dalam hitungan detik.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <i class="fa-solid fa-chair text-primary text-3xl mb-4"></i>
            <h4 class="font-bold text-xl mb-2">Pilih Kursi</h4>
            <p class="text-gray-600">Pilih posisi duduk sesuai kenyamanan kamu.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <i class="fa-solid fa-map-location-dot text-primary text-3xl mb-4"></i>
            <h4 class="font-bold text-xl mb-2">Live Tracking</h4>
            <p class="text-gray-600">Pantau posisi shuttle secara real-time.</p>
        </div>

    </div>
</section>

<section id="cara" class="py-16 px-8">
    <h3 class="text-3xl font-bold text-center mb-12">Cara Menggunakan</h3>

    <div class="grid md:grid-cols-3 gap-8 text-center">

        <div>
            <i class="fa-solid fa-download text-primary text-3xl mb-4"></i>
            <h4 class="font-bold mb-2">Download</h4>
            <p class="text-gray-600">Install aplikasi dari Play Store.</p>
        </div>

        <div>
            <i class="fa-solid fa-magnifying-glass text-primary text-3xl mb-4"></i>
            <h4 class="font-bold mb-2">Cari Jadwal</h4>
            <p class="text-gray-600">Pilih rute dan jadwal perjalanan.</p>
        </div>

        <div>
            <i class="fa-solid fa-bus text-primary text-3xl mb-4"></i>
            <h4 class="font-bold mb-2">Berangkat</h4>
            <p class="text-gray-600">Nikmati perjalanan dengan mudah.</p>
        </div>

    </div>
</section>

<section id="download" class="bg-primary text-white py-16 text-center">
    <h3 class="text-3xl font-bold mb-4">Download Sekarang</h3>
    <p class="mb-6">Gunakan GASSIN untuk pengalaman shuttle yang lebih modern.</p>

    <a href="#" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold flex items-center justify-center gap-2 w-fit mx-auto hover:bg-gray-100">
        <i class="fa-brands fa-google-play"></i>
        Download di Play Store
    </a>
</section>

<footer class="py-6 text-center text-gray-500">
    <p>© 2026 GASSIN Shuttle System</p>
</footer>

@endsection