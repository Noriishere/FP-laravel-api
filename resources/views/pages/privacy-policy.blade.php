{{-- resources/views/privacy-policy.blade.php --}}

@extends('layouts.landing-app')

@section('title', 'Privacy Policy — GASSIN')

@section('content')

    <section class="relative overflow-hidden bg-dark py-28">

        {{-- Background --}}
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute left-1/2 top-1/2 h-[700px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-3xl">
            </div>
            <div class="absolute -right-20 top-0 h-[300px] w-[300px] rounded-full bg-orange-500/10 blur-3xl"></div>
            <div class="absolute -left-20 bottom-0 h-[300px] w-[300px] rounded-full bg-primary/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-5xl px-6 lg:px-12 text-center">

            <div
                class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white/70">
                <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                Privacy & Data Protection
            </div>

            <h1 class="font-fraunces mt-8 text-5xl font-black leading-tight text-white lg:text-6xl">
                Privacy Policy
            </h1>

            <p class="mx-auto mt-8 max-w-2xl text-base leading-8 text-white/60">
                Kami menghargai privasi pengguna dan berkomitmen untuk menjaga keamanan data
                yang dikumpulkan melalui aplikasi GASSIN.
            </p>

            <div
                class="mt-8 inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm text-white/50">
                <i class="fa-solid fa-calendar-days text-primary"></i>
                Terakhir diperbarui: 24 Mei 2026
            </div>

        </div>

    </section>

    <section class="relative bg-bg px-6 py-20 lg:px-12">

        <div class="mx-auto max-w-5xl">

            {{-- Top cards --}}
            <div class="grid gap-5 lg:grid-cols-3">

                <div class="rounded-3xl border border-borderColor bg-white p-7 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                        <i class="fa-solid fa-shield-halved text-xl text-primary"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Data Aman
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Kami menggunakan langkah keamanan yang wajar untuk melindungi data pengguna.
                    </p>
                </div>

                <div class="rounded-3xl border border-borderColor bg-white p-7 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100">
                        <i class="fa-solid fa-location-dot text-xl text-green-600"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Lokasi Real-time
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Lokasi hanya digunakan untuk fitur tracking shuttle dan estimasi perjalanan.
                    </p>
                </div>

                <div class="rounded-3xl border border-borderColor bg-white p-7 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100">
                        <i class="fa-solid fa-credit-card text-xl text-orange-500"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Pembayaran Aman
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Semua transaksi diproses melalui payment gateway pihak ketiga terpercaya.
                    </p>
                </div>

            </div>

            {{-- Main content --}}
            <div class="mt-8 rounded-[32px] border border-borderColor bg-white p-8 shadow-sm lg:p-14">

                <div class="space-y-16">

                    {{-- Intro --}}
                    <div>
                        <div class="section-label">Pendahuluan</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Komitmen kami terhadap privasi pengguna
                        </h2>

                        <div class="mt-6 space-y-5 text-[15px] leading-8 text-grayText">
                            <p>
                                GASSIN adalah platform pemesanan shuttle modern yang memungkinkan
                                pengguna memesan perjalanan, memilih kursi, melakukan pembayaran digital,
                                dan melacak posisi shuttle secara real-time.
                            </p>

                            <p>
                                Dengan menggunakan aplikasi GASSIN, pengguna menyetujui pengumpulan
                                dan penggunaan informasi sesuai dengan Kebijakan Privasi ini.
                            </p>
                        </div>
                    </div>

                    {{-- Data collected --}}
                    <div>

                        <div class="section-label">Informasi</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Informasi yang kami kumpulkan
                        </h2>

                        <div class="mt-8 grid gap-5 lg:grid-cols-2">

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Informasi Akun
                                </h3>

                                <ul class="mt-4 space-y-3 text-sm text-grayText">
                                    <li>• Nama pengguna</li>
                                    <li>• Email</li>
                                    <li>• Nomor telepon</li>
                                </ul>
                            </div>

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Informasi Perjalanan
                                </h3>

                                <ul class="mt-4 space-y-3 text-sm text-grayText">
                                    <li>• Riwayat pemesanan</li>
                                    <li>• Jadwal perjalanan</li>
                                    <li>• Pilihan kursi</li>
                                    <li>• Titik penjemputan</li>
                                </ul>
                            </div>

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Informasi Lokasi
                                </h3>

                                <ul class="mt-4 space-y-3 text-sm text-grayText">
                                    <li>• Live tracking shuttle</li>
                                    <li>• Estimasi waktu tiba</li>
                                    <li>• Lokasi penjemputan terdekat</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    {{-- Usage --}}
                    <div>

                        <div class="section-label">Penggunaan Data</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Bagaimana data digunakan
                        </h2>

                        <div class="mt-8 grid gap-4">

                            @php
                                $usages = [
                                    'Memproses pemesanan shuttle',
                                    'Mengelola akun pengguna',
                                    'Mengirim notifikasi perjalanan',
                                    'Menyediakan fitur live tracking',
                                    'Meningkatkan performa aplikasi',
                                    'Mendeteksi aktivitas mencurigakan',
                                ];
                            @endphp

                            @foreach ($usages as $usage)
                                <div class="flex items-start gap-4 rounded-2xl border border-borderColor bg-bg px-5 py-4">
                                    <div
                                        class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-primary text-white">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>

                                    <p class="text-sm text-dark font-medium">
                                        {{ $usage }}
                                    </p>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- Security --}}
                    <div>

                        <div class="section-label">Keamanan</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Perlindungan data pengguna
                        </h2>

                        <div class="mt-6 rounded-3xl bg-dark p-8 text-white">

                            <div class="grid gap-8 lg:grid-cols-3">

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-lock text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Enkripsi
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Semua koneksi dilakukan melalui HTTPS terenkripsi.
                                    </p>
                                </div>

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-server text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Infrastruktur Aman
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Data disimpan menggunakan layanan cloud terpercaya.
                                    </p>
                                </div>

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-user-shield text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Kontrol Pengguna
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Pengguna dapat meminta penghapusan akun dan data pribadi.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Contact --}}
                    <div>

                        <div class="section-label">Kontak</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Hubungi kami
                        </h2>

                        <div class="mt-8 rounded-3xl border border-borderColor bg-bg p-8">

                            <div class="grid gap-6 lg:grid-cols-3">

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-grayText">
                                        Email
                                    </p>

                                    <p class="mt-2 font-semibold text-dark">
                                        naltydev@gmail.com
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-grayText">
                                        Aplikasi
                                    </p>

                                    <p class="mt-2 font-semibold text-dark">
                                        GASSIN Shuttle System
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-grayText">
                                        Lokasi
                                    </p>

                                    <p class="mt-2 font-semibold text-dark">
                                        Indonesia
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>
                    {{-- Account Deletion --}}
                    <div>

                        <div class="section-label">Penghapusan Akun</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Penghapusan akun & data pengguna
                        </h2>

                        <div class="mt-6 space-y-5 text-[15px] leading-8 text-grayText">

                            <p>
                                Pengguna memiliki hak untuk meminta penghapusan akun
                                dan data pribadi yang tersimpan di sistem GASSIN.
                            </p>

                            <p>
                                Permintaan penghapusan data dapat dilakukan dengan
                                menghubungi tim kami melalui email resmi yang tersedia
                                pada halaman ini.
                            </p>

                            <p>
                                Setelah permintaan diverifikasi, kami akan memproses
                                penghapusan akun dan data terkait sesuai dengan
                                kebijakan dan ketentuan yang berlaku.
                            </p>

                        </div>

                        <div class="mt-8 rounded-3xl border border-borderColor bg-bg p-6">

                            <div class="flex items-start gap-4">

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50">
                                    <i class="fa-solid fa-trash text-red-500"></i>
                                </div>

                                <div class="w-full">

                                    <h3 class="text-lg font-bold text-dark">
                                        Request Account Deletion
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-grayText">
                                        Masukkan email akun yang ingin dihapus. Kami akan memproses permintaan penghapusan
                                        akun Anda.
                                    </p>

                                    @if (session('success'))
                                        <div class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="mt-4 rounded-2xl bg-blue-50 p-4 text-sm text-blue-700">
                                        Permintaan penghapusan akun akan diproses maksimal dalam 7 hari kerja setelah
                                        verifikasi.
                                    </div>
                                    <form action="{{ route('account.deletion.request') }}" method="POST" class="mt-5">
                                        @csrf

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-dark">
                                                Email
                                            </label>

                                            <input type="email" name="email" value="{{ old('email') }}"
                                                placeholder="example@email.com"
                                                class="w-full rounded-2xl border border-borderColor bg-white px-4 py-3 outline-none transition focus:border-primary"
                                                required>

                                            @error('email')
                                                <p class="mt-2 text-sm text-red-500">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-red-500 px-5 py-3 font-semibold text-white transition hover:bg-red-600">
                                            <i class="fa-solid fa-trash"></i>
                                            Request Deletion
                                        </button>
                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
