{{-- resources/views/terms-of-service.blade.php --}}

@extends('layouts.landing-app')

@section('title', 'Terms of Service — GASSIN')

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
                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                Terms & Conditions
            </div>

            <h1 class="font-fraunces mt-8 text-5xl font-black leading-tight text-white lg:text-6xl">
                Terms of Service
            </h1>

            <p class="mx-auto mt-8 max-w-2xl text-base leading-8 text-white/60">
                Syarat dan Ketentuan penggunaan aplikasi GASSIN bagi seluruh pengguna layanan shuttle booking kami.
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
                        <i class="fa-solid fa-user-check text-xl text-primary"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Pengguna Bertanggung Jawab
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Pengguna wajib menjaga keamanan akun dan data login masing-masing.
                    </p>
                </div>

                <div class="rounded-3xl border border-borderColor bg-white p-7 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100">
                        <i class="fa-solid fa-credit-card text-xl text-green-600"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Pembayaran Aman
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Semua pembayaran diproses melalui payment gateway terpercaya.
                    </p>
                </div>

                <div class="rounded-3xl border border-borderColor bg-white p-7 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100">
                        <i class="fa-solid fa-ban text-xl text-orange-500"></i>
                    </div>

                    <h3 class="mt-5 font-fraunces text-2xl font-bold text-dark">
                        Penyalahgunaan Dilarang
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-grayText">
                        Aktivitas ilegal, spam, dan penyalahgunaan sistem tidak diperbolehkan.
                    </p>
                </div>

            </div>

            {{-- Main content --}}
            <div class="mt-8 rounded-[32px] border border-borderColor bg-white p-8 shadow-sm lg:p-14">

                <div class="space-y-16">

                    {{-- Acceptance --}}
                    <div>

                        <div class="section-label">Persetujuan</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Penerimaan Ketentuan
                        </h2>

                        <div class="mt-6 space-y-5 text-[15px] leading-8 text-grayText">
                            <p>
                                Dengan mengakses dan menggunakan aplikasi GASSIN,
                                pengguna dianggap telah membaca, memahami,
                                dan menyetujui seluruh syarat dan ketentuan yang berlaku.
                            </p>

                            <p>
                                Jika pengguna tidak menyetujui ketentuan ini,
                                maka pengguna disarankan untuk tidak menggunakan layanan GASSIN.
                            </p>
                        </div>

                    </div>

                    {{-- Services --}}
                    <div>

                        <div class="section-label">Layanan</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Layanan GASSIN
                        </h2>

                        <div class="mt-8 grid gap-5 lg:grid-cols-2">

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Shuttle Booking
                                </h3>

                                <p class="mt-4 text-sm leading-7 text-grayText">
                                    Pengguna dapat memesan shuttle, memilih jadwal,
                                    memilih kursi, dan mengakses tiket digital.
                                </p>
                            </div>

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Live Tracking
                                </h3>

                                <p class="mt-4 text-sm leading-7 text-grayText">
                                    Aplikasi menyediakan fitur pelacakan shuttle secara real-time.
                                </p>
                            </div>

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Pembayaran Digital
                                </h3>

                                <p class="mt-4 text-sm leading-7 text-grayText">
                                    Transaksi dilakukan menggunakan payment gateway pihak ketiga.
                                </p>
                            </div>

                            <div class="rounded-3xl bg-bg p-6 border border-borderColor">
                                <h3 class="font-bold text-dark text-lg">
                                    Pengembangan Sistem
                                </h3>

                                <p class="mt-4 text-sm leading-7 text-grayText">
                                    GASSIN dapat memperbarui atau mengubah layanan sewaktu-waktu.
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- User obligations --}}
                    <div>

                        <div class="section-label">Kewajiban Pengguna</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Tanggung jawab pengguna
                        </h2>

                        <div class="mt-8 grid gap-4">

                            @php
                                $rules = [
                                    'Memberikan data yang valid dan akurat',
                                    'Menjaga keamanan akun dan password',
                                    'Tidak menyalahgunakan layanan aplikasi',
                                    'Tidak mencoba mengakses sistem tanpa izin',
                                    'Tidak menyebarkan malware atau spam',
                                    'Mematuhi hukum dan peraturan yang berlaku',
                                ];
                            @endphp

                            @foreach ($rules as $rule)
                                <div class="flex items-start gap-4 rounded-2xl border border-borderColor bg-bg px-5 py-4">

                                    <div
                                        class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-primary text-white">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>

                                    <p class="text-sm font-medium text-dark">
                                        {{ $rule }}
                                    </p>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- Payment --}}
                    <div>

                        <div class="section-label">Pembayaran</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Kebijakan pembayaran & refund
                        </h2>

                        <div class="mt-6 rounded-3xl bg-dark p-8 text-white">

                            <div class="grid gap-8 lg:grid-cols-3">

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-wallet text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Payment Gateway
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Semua pembayaran diproses oleh pihak ketiga terpercaya.
                                    </p>
                                </div>

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-rotate-left text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Refund
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Refund mengikuti kebijakan operator shuttle terkait.
                                    </p>
                                </div>

                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                                        <i class="fa-solid fa-circle-exclamation text-primary"></i>
                                    </div>

                                    <h3 class="mt-5 font-bold">
                                        Validasi Data
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-white/60">
                                        Pengguna wajib memastikan data transaksi sudah benar.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Liability --}}
                    <div>

                        <div class="section-label">Batasan</div>

                        <h2 class="font-fraunces text-4xl font-black text-dark">
                            Batasan tanggung jawab
                        </h2>

                        <div class="mt-6 space-y-5 text-[15px] leading-8 text-grayText">

                            <p>
                                GASSIN berupaya menyediakan layanan terbaik,
                                namun tidak menjamin aplikasi akan selalu bebas gangguan,
                                bug, atau downtime.
                            </p>

                            <p>
                                GASSIN tidak bertanggung jawab atas kerugian
                                yang timbul akibat penggunaan layanan di luar kendali kami.
                            </p>

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

                </div>

            </div>

        </div>

    </section>

@endsection
