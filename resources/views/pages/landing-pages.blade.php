@extends('layouts.landing-app')

@section('title', 'GASSIN')

@section('content')

<section class="relative overflow-hidden px-6 py-10 lg:px-12">

    <div class="absolute -right-24 -top-24 h-[520px] w-[520px] rounded-full bg-primary/10 blur-3xl"></div>

    <div class="absolute bottom-10 left-10 h-[320px] w-[320px] rounded-full bg-orange-400/10 blur-3xl"></div>

    <div class="mx-auto grid min-h-[calc(100vh-70px)] max-w-7xl items-center gap-16 lg:grid-cols-2">

        <div>

            <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary">

                <span class="h-2 w-2 animate-pulse rounded-full bg-primary"></span>

                Shuttle Booking Modern

            </div>

            <h1 class="font-fraunces text-5xl font-black leading-tight text-dark lg:text-6xl">

                Shuttle Lebih <br>

                <span class="text-primary">
                    Cepat & Praktis
                </span>

            </h1>

            <p class="mt-6 max-w-xl text-lg leading-8 text-grayText">

                GASSIN adalah aplikasi booking shuttle modern yang memudahkan kamu memesan perjalanan,
                memilih kursi, dan tracking posisi shuttle langsung dari smartphone.

            </p>

            <div class="mt-10 flex flex-wrap gap-4">

                <a href="#"
                   class="inline-flex items-center gap-3 rounded-2xl bg-primary px-7 py-4 text-sm font-bold text-white shadow-lg shadow-primary/30 transition hover:-translate-y-1 hover:bg-primaryDark">

                    <i class="fa-brands fa-google-play"></i>

                    Download di Play Store

                </a>

                <a href="#fitur"
                   class="inline-flex items-center gap-3 rounded-2xl border border-borderColor bg-white px-7 py-4 text-sm font-semibold text-dark transition hover:-translate-y-1 hover:border-primary hover:text-primary">

                    <i class="fa-solid fa-circle-play"></i>

                    Lihat Fitur

                </a>

            </div>

            <div class="mt-14 flex flex-wrap gap-10 border-t border-borderColor pt-10">

                <div>
                    <h3 class="font-fraunces text-3xl font-black text-dark">
                        10K+
                    </h3>
                    <p class="mt-1 text-sm text-grayText">
                        Pengguna Aktif
                    </p>
                </div>

                <div>
                    <h3 class="font-fraunces text-3xl font-black text-dark">
                        98%
                    </h3>
                    <p class="mt-1 text-sm text-grayText">
                        Kepuasan
                    </p>
                </div>

                <div>
                    <h3 class="font-fraunces text-3xl font-black text-dark">
                        50+
                    </h3>
                    <p class="mt-1 text-sm text-grayText">
                        Rute Tersedia
                    </p>
                </div>

            </div>

        </div>

        <div class="relative flex items-center justify-center">

            <div class="absolute -right-6 -top-6 h-28 w-28 bg-[radial-gradient(circle,#d0cbc2_1.5px,transparent_1.5px)] bg-[length:14px_14px] opacity-60"></div>

            <div class="relative aspect-[4/3] w-full max-w-2xl overflow-hidden rounded-[28px] border border-borderColor bg-gradient-to-br from-[#f0ede8] to-[#e8e3dc] shadow-2xl">

                <img
                    src="#"
                    alt="GASSIN Shuttle"
                    class="h-full w-full object-cover hidden"
                    onload="this.classList.remove('hidden'); this.nextElementSibling.classList.add('hidden');"
                >

                <div class="flex h-full flex-col items-center justify-center gap-5">

                    <i class="fa-solid fa-bus text-7xl text-primary/20"></i>

                    <p class="text-sm font-semibold tracking-widest text-gray-400">
                        GANTI SRC DENGAN GAMBAR ANDA
                    </p>

                </div>

            </div>

            <div class="absolute bottom-5 left-5 flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-4 shadow-xl backdrop-blur">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-white">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-dark">
                        Live Tracking
                    </h4>

                    <p class="text-xs text-grayText">
                        Pantau posisi real-time
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

<section id="fitur" class="border-t border-borderColor bg-white px-6 py-24 lg:px-12">

    <div class="mx-auto max-w-7xl">

        <div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">

            <div class="h-[2px] w-6 rounded-full bg-primary"></div>

            Fitur Unggulan

        </div>

        <h2 class="max-w-xl font-fraunces text-4xl font-black leading-tight text-dark">
            Semua yang kamu butuhkan, dalam satu genggaman
        </h2>

        <div class="mt-16 grid gap-6 lg:grid-cols-3">

            <div class="rounded-3xl border border-borderColor bg-bg p-9 transition hover:-translate-y-2 hover:border-primary/30 hover:shadow-2xl">

                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                    <i class="fa-solid fa-ticket text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Booking Instan
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Pesan shuttle hanya dalam hitungan detik. Tanpa antrian, tanpa ribet.
                </p>

            </div>

            <div class="rounded-3xl border border-borderColor bg-bg p-9 transition hover:-translate-y-2 hover:border-primary/30 hover:shadow-2xl">

                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                    <i class="fa-solid fa-chair text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Pilih Kursi
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Pilih posisi duduk favoritmu sesuai kenyamanan dan kebutuhan perjalanan.
                </p>

            </div>

            <div class="rounded-3xl border border-borderColor bg-bg p-9 transition hover:-translate-y-2 hover:border-primary/30 hover:shadow-2xl">

                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                    <i class="fa-solid fa-map-location-dot text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Live Tracking
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Pantau posisi shuttle secara real-time tanpa tebak-tebakan.
                </p>

            </div>

        </div>

    </div>

</section>

<section id="cara" class="border-t border-borderColor bg-bg px-6 py-24 lg:px-12">

    <div class="mx-auto max-w-7xl">

        <div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">

            <div class="h-[2px] w-6 rounded-full bg-primary"></div>

            Cara Kerja

        </div>

        <h2 class="max-w-xl font-fraunces text-4xl font-black leading-tight text-dark">
            Tiga langkah mudah untuk berangkat
        </h2>

        <div class="relative mt-20 grid gap-10 lg:grid-cols-3">

            <div class="text-center">

                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border-2 border-borderColor bg-white">
                    <i class="fa-solid fa-download text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Download Aplikasi
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Install GASSIN gratis dari Google Play Store di smartphone kamu.
                </p>

            </div>

            <div class="text-center">

                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border-2 border-borderColor bg-white">
                    <i class="fa-solid fa-magnifying-glass text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Cari Jadwal
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Pilih rute dan jadwal keberangkatan sesuai rencana perjalananmu.
                </p>

            </div>

            <div class="text-center">

                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border-2 border-borderColor bg-white">
                    <i class="fa-solid fa-bus text-xl text-primary"></i>
                </div>

                <h3 class="font-fraunces text-2xl font-bold text-dark">
                    Berangkat!
                </h3>

                <p class="mt-4 leading-7 text-grayText">
                    Scan tiket digital dan nikmati perjalanan shuttle yang nyaman.
                </p>

            </div>

        </div>

    </div>

</section>

<section id="driver" class="border-t border-borderColor bg-white px-6 py-24 lg:px-12">

    <div class="mx-auto grid max-w-7xl items-center gap-20 lg:grid-cols-2">

        <div>

            <div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">

                <div class="h-[2px] w-6 rounded-full bg-primary"></div>

                Bergabung Bersama Kami

            </div>

            <h2 class="font-fraunces text-5xl font-black leading-tight text-dark">
                Jadilah driver GASSIN,
                kendalikan penghasilanmu
            </h2>

            <p class="mt-6 max-w-xl leading-8 text-grayText">
                Bergabunglah dengan ribuan driver GASSIN dan nikmati fleksibilitas kerja,
                penghasilan kompetitif, serta dukungan penuh dari tim kami.
            </p>

        </div>

    </div>

</section>

<section id="download" class="relative overflow-hidden bg-dark px-6 py-28 text-center lg:px-12">

    <div class="absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/20 blur-3xl"></div>

    <div class="relative mx-auto max-w-3xl">

        <h2 class="font-fraunces text-5xl font-black text-white">
            Siap untuk perjalanan lebih baik?
        </h2>

        <p class="mt-6 text-lg leading-8 text-white/60">
            Gabung bersama ribuan pengguna GASSIN dan rasakan kemudahan booking shuttle.
        </p>

        <a href="#"
           class="mt-10 inline-flex items-center gap-3 rounded-2xl bg-primary px-8 py-5 text-sm font-bold text-white shadow-2xl shadow-primary/30 transition hover:-translate-y-1 hover:bg-primaryLight">

            <i class="fa-brands fa-google-play"></i>

            Download Sekarang — Gratis

        </a>

    </div>

</section>

<footer class="flex flex-col items-center justify-between gap-3 border-t border-borderColor bg-white px-6 py-8 text-center lg:flex-row lg:px-12">

    <span class="font-fraunces text-2xl font-black text-primary">
        GASSIN
    </span>

    <p class="text-sm text-grayText">
        © 2026 GASSIN Shuttle System. All rights reserved.
    </p>

</footer>

@endsection