@extends('layouts.guest')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="bg-gradient-to-r from-primary to-cyan-500 rounded-3xl p-10 text-white shadow-lg mb-8">

        <div class="max-w-3xl">

            <h1 class="text-4xl font-bold leading-tight">
                Daftar Menjadi Supir Resmi Gassin!
            </h1>

            <p class="mt-4 text-white/90 text-lg leading-relaxed">
                Bergabunglah bersama Gassin! dan jadilah bagian dari layanan transportasi terpercaya. Upload dokumen verifikasi Anda untuk memulai perjalanan sebagai driver resmi kami.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">

                <a
                    href="{{ route('driver.documents') }}"
                    class="bg-white text-primary px-6 py-3 rounded-2xl font-semibold hover:opacity-90 transition"
                >
                    Upload Dokumen
                </a>

            </div>

        </div>

    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow p-6">

            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-id-card"></i>
            </div>

            <h3 class="text-xl font-semibold mb-3">
                Verifikasi Mudah
            </h3>

            <p class="text-gray-500 leading-relaxed">
                Upload KTP, SIM, dan selfie untuk proses verifikasi driver dengan cepat dan aman.
            </p>

        </div>

        <div class="bg-white rounded-2xl shadow p-6">

            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-route"></i>
            </div>

            <h3 class="text-xl font-semibold mb-3">
                Jadwal Fleksibel
            </h3>

            <p class="text-gray-500 leading-relaxed">
                Ambil perjalanan sesuai jadwal yang tersedia dan kelola trip langsung dari dashboard driver.
            </p>

        </div>

        <div class="bg-white rounded-2xl shadow p-6">

            <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <h3 class="text-xl font-semibold mb-3">
                Sistem Aman
            </h3>

            <p class="text-gray-500 leading-relaxed">
                Driver terverifikasi akan mendapatkan akses penuh ke sistem perjalanan dan tracking realtime.
            </p>

        </div>

    </div>

</div>

@endsection