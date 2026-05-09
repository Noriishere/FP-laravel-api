@extends('layouts.guest')

@section('content')

<nav class="border-b bg-white sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="#" class="text-2xl font-bold text-primary">
            Driver <i>Gassin!</i>
        </a>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('driver.login') }}"
                class="px-5 py-2 rounded-xl hover:bg-gray-100 transition"
            >
                Login
            </a>

            <a
                href="{{ route('driver.register') }}"
                class="bg-primary text-white px-5 py-2 rounded-xl hover:opacity-90 transition"
            >
                Daftar Driver
            </a>

        </div>

    </div>

</nav>

<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="grid lg:grid-cols-2 gap-14 items-center">

        <div>

            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fa-solid fa-car-side"></i>
                Driver Partner Program
            </div>

            <h1 class="text-5xl font-bold leading-tight">
                Daftar Jadi Supir Resmi
                <span class="text-primary">
                    Gassin!
                </span>
            </h1>

            <p class="mt-6 text-lg text-gray-500 leading-relaxed">
                Bergabung bersama Gassin! dan mulai perjalanan Anda sebagai driver terpercaya dengan sistem shuttle modern, realtime tracking, dan manajemen trip yang mudah.
            </p>

            <div class="mt-10 flex flex-wrap gap-4">

                <a
                    href="{{ route('driver.register') }}"
                    class="bg-primary text-white px-7 py-4 rounded-2xl font-semibold hover:opacity-90 transition"
                >
                    Daftar Sekarang
                </a>

                <a
                    href="{{ route('driver.login') }}"
                    class="border border-gray-300 px-7 py-4 rounded-2xl font-semibold hover:bg-gray-100 transition"
                >
                    Login Driver
                </a>

            </div>

            <div class="mt-12 grid grid-cols-3 gap-6">

                <div>

                    <h3 class="text-3xl font-bold text-primary">
                        100+
                    </h3>

                    <p class="text-gray-500 mt-1 text-sm">
                        Driver Aktif
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-bold text-primary">
                        24/7
                    </h3>

                    <p class="text-gray-500 mt-1 text-sm">
                        Support System
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-bold text-primary">
                        Realtime
                    </h3>

                    <p class="text-gray-500 mt-1 text-sm">
                        Tracking
                    </p>

                </div>

            </div>

        </div>

        <div>

            <div class="bg-white border rounded-3xl shadow-xl p-8">

                <div class="grid gap-5">

                    <div class="flex items-start gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-id-card"></i>
                        </div>

                        <div>

                            <h3 class="text-xl font-semibold">
                                Verifikasi Mudah
                            </h3>

                            <p class="text-gray-500 mt-2 leading-relaxed">
                                Upload dokumen seperti KTP dan SIM untuk memulai proses verifikasi driver.
                            </p>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-route"></i>
                        </div>

                        <div>

                            <h3 class="text-xl font-semibold">
                                Sistem Modern
                            </h3>

                            <p class="text-gray-500 mt-2 leading-relaxed">
                                Nikmati dashboard driver modern dengan realtime tracking dan pengelolaan trip otomatis.
                            </p>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                        <div>

                            <h3 class="text-xl font-semibold">
                                Aman & Terpercaya
                            </h3>

                            <p class="text-gray-500 mt-2 leading-relaxed">
                                Semua driver diverifikasi untuk menjaga kualitas layanan shuttle Gassin!.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection