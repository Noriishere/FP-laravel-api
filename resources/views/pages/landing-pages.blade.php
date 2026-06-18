@extends('layouts.landing-app')

@section('title', 'GASSIN — Shuttle Booking Modern')

@push('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-gradient {
            background: radial-gradient(ellipse 80% 60% at 70% 40%, rgba(232, 44, 44, 0.07) 0%, transparent 70%);
        }

        .card-hover {
            transition: transform 0.3s cubic-bezier(.22, 1, .36, 1), box-shadow 0.3s, border-color 0.3s;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 64px rgba(232, 44, 44, 0.10);
            border-color: rgba(232, 44, 44, 0.25);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #E82C2C;
            border-radius: 99px;
            transition: width 0.25s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 28s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .stat-bar {
            height: 6px;
            border-radius: 99px;
            background: #F0EDE8;
            overflow: hidden;
        }

        .stat-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #E82C2C, #FF4B4B);
            transition: width 1.2s cubic-bezier(.22, 1, .36, 1);
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #E82C2C;
            margin-bottom: 16px;
        }

        .section-label::before {
            content: '';
            width: 24px;
            height: 2px;
            border-radius: 99px;
            background: #E82C2C;
        }

        .pricing-card {
            background: #fff;
            border: 1.5px solid #E8E3DC;
            border-radius: 24px;
            padding: 32px;
            transition: box-shadow 0.3s, border-color 0.3s, transform 0.3s;
        }

        .pricing-card:hover {
            box-shadow: 0 20px 56px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .pricing-card.featured {
            border-color: #E82C2C;
            box-shadow: 0 8px 48px rgba(232, 44, 44, 0.12);
        }

        .testimonial-card {
            background: #fff;
            border: 1px solid #E8E3DC;
            border-radius: 20px;
            padding: 24px;
        }

        .dot-pattern {
            background-image: radial-gradient(circle, #d0cbc2 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        .btn-primary {
            background: #E82C2C;
            color: #fff;
            border-radius: 14px;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px rgba(232, 44, 44, 0.25);
        }

        .btn-primary:hover {
            background: #C41F1F;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(232, 44, 44, 0.35);
        }

        .btn-outline {
            border: 1.5px solid #E8E3DC;
            background: #fff;
            color: #111010;
            border-radius: 14px;
            padding: 13px 24px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s, border-color 0.2s, color 0.2s;
        }

        .btn-outline:hover {
            border-color: #E82C2C;
            color: #E82C2C;
            transform: translateY(-2px);
        }

        .faq-item {
            border-bottom: 1px solid #E8E3DC;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-answer {
            display: none;
        }

        .faq-item.open .faq-answer {
            display: block;
        }

        .faq-item.open .faq-icon {
            transform: rotate(45deg);
        }

        .faq-icon {
            transition: transform 0.25s;
        }

        .step-connector {
            position: absolute;
            top: 32px;
            left: calc(50% + 40px);
            width: calc(100% - 80px);
            height: 1px;
            background: linear-gradient(90deg, #E8E3DC 60%, transparent 100%);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes marquee {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-slow {
            animation: floatSlow 9s ease-in-out infinite;
        }

        .animate-slide-up {
            animation: slideUp 0.7s ease forwards;
        }

        @media (max-width: 768px) {
            .step-connector {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ========== HERO ========== --}}
    <section class="relative overflow-hidden px-6 py-10 lg:px-12 hero-gradient">

        <div class="absolute -right-32 -top-32 h-[560px] w-[560px] rounded-full bg-primary/8 blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 -left-20 h-[300px] w-[300px] rounded-full bg-orange-400/8 blur-3xl pointer-events-none">
        </div>
        <div class="absolute right-8 top-24 h-32 w-32 dot-pattern opacity-50 pointer-events-none hidden lg:block"></div>

        <div class="mx-auto grid min-h-[calc(100vh-70px)] max-w-7xl items-center gap-16 lg:grid-cols-2">

            {{-- Left copy --}}
            <div class="animate-slide-up">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary"
                    style="box-shadow:0 0 0 4px rgba(232,44,44,0.08)">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-primary"></span>
                    Shuttle Booking Modern
                </div>

                <h1 class="font-fraunces text-5xl font-black leading-[1.1] text-dark lg:text-6xl">
                    Shuttle Lebih<br>
                    <span class="text-primary relative">
                        Cepat &amp; Praktis
                        <svg class="absolute -bottom-2 left-0 w-full" height="8" viewBox="0 0 300 8" fill="none">
                            <path d="M2 6 C50 2, 150 2, 298 6" stroke="#E82C2C" stroke-width="3" stroke-linecap="round"
                                fill="none" opacity="0.4" />
                        </svg>
                    </span>
                </h1>

                <p class="mt-8 max-w-lg text-base leading-8 text-grayText">
                    GASSIN adalah platform booking shuttle modern — pesan tiket, pilih kursi, pantau posisi real-time, dan
                    bayar digital, semuanya dalam satu aplikasi.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="https://play.google.com/store/apps/details?id=com.naltylabs.gassin&pcampaignid=web_share" target="_blank" class="btn-primary">
                        <i class="fa-brands fa-google-play"></i>
                        Download di Play Store
                    </a>
                    <a href="#fitur" class="btn-outline">
                        <i class="fa-solid fa-circle-play text-primary"></i>
                        Lihat Fitur
                    </a>
                </div>

                {{-- Stats --}}
                <div class="mt-14 grid grid-cols-3 gap-6 border-t border-borderColor pt-10">

                    <div>
                        <div class="flex items-end gap-0.5">
                            <h3
                                class="font-fraunces text-3xl font-black text-dark counter"
                                data-target="{{ $stats['bookings'] }}">
                                0
                            </h3>
                            <span class="font-fraunces text-3xl font-black text-dark">+</span>
                        </div>
                        <p class="mt-1 text-xs text-grayText font-medium">
                            Perjalanan
                        </p>
                    </div>

                    <div>
                        <div class="flex items-end gap-0.5">
                            <h3
                                class="font-fraunces text-3xl font-black text-dark counter"
                                data-target="{{ $stats['drivers'] }}">
                                0
                            </h3>
                            <span class="font-fraunces text-3xl font-black text-dark">+</span>
                        </div>
                        <p class="mt-1 text-xs text-grayText font-medium">
                            Driver Aktif
                        </p>
                    </div>

                    <div>
                        <div class="flex items-end gap-0.5">
                            <h3
                                class="font-fraunces text-3xl font-black text-dark counter"
                                data-target="{{ $stats['routes'] }}">
                                0
                            </h3>
                            <span class="font-fraunces text-3xl font-black text-dark">+</span>
                        </div>
                        <p class="mt-1 text-xs text-grayText font-medium">
                            Rute Tersedia
                        </p>
                    </div>

                </div>

            </div>

            {{-- Right — App Mockup --}}
            <div class="relative flex items-center justify-center">

                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="h-72 w-72 rounded-full bg-primary/6 blur-2xl"></div>
                </div>

                {{-- Phone mockup --}}
                <div
                    class="animate-float relative z-10 w-full max-w-sm rounded-[32px] bg-white border border-borderColor shadow-2xl overflow-hidden">

                    {{-- App header --}}
                    <div class="bg-primary px-6 pt-6 pb-10">
                        <div class="flex items-center justify-between mb-6">
                            <span class="font-fraunces text-xl font-black text-white">GASSIN</span>
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full bg-white/40"></div>
                                <div class="h-2 w-2 rounded-full bg-white/40"></div>
                                <div class="h-2 w-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <p class="text-white/70 text-xs font-medium mb-1">Mau ke mana hari ini?</p>
                        <h2 class="text-white font-bold text-xl">Pilih Perjalananmu</h2>
                    </div>

                    {{-- Search card --}}
                    <div class="-mt-5 mx-5 rounded-2xl bg-white border border-borderColor shadow-lg p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex flex-col items-center gap-1">
                                <div class="h-3 w-3 rounded-full bg-primary/30 border-2 border-primary"></div>
                                <div class="h-6 w-px bg-borderColor"></div>
                                <div class="h-3 w-3 rounded-full bg-primary"></div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-grayText mb-1">Dari</p>
                                <p class="text-sm font-semibold text-dark">Terminal Karawang</p>
                                <div class="h-px bg-borderColor my-2"></div>
                                <p class="text-xs text-grayText mb-1">Ke</p>
                                <p class="text-sm font-semibold text-dark">Kampus Telkom University</p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <div
                                class="flex-1 bg-bg rounded-xl px-3 py-2 text-xs font-semibold text-dark flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-primary text-xs"></i>
                                Sabtu, 24 Mei
                            </div>
                            <div
                                class="flex-1 bg-bg rounded-xl px-3 py-2 text-xs font-semibold text-dark flex items-center gap-2">
                                <i class="fa-regular fa-clock text-primary text-xs"></i>
                                07.00 WIB
                            </div>
                        </div>
                    </div>

                    {{-- Schedule list --}}
                    <div class="px-5 pt-4 pb-2">
                        <p class="text-xs font-bold text-grayText uppercase tracking-wider mb-3">Jadwal Tersedia</p>
                        <div class="space-y-2">
                            <div
                                class="flex items-center justify-between rounded-xl bg-primary/5 border border-primary/20 px-4 py-3">
                                <div>
                                    <p class="font-bold text-sm text-dark">07.00 → 08.30</p>
                                    <p class="text-xs text-grayText mt-0.5">Shuttle Express · 12 kursi</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-primary text-sm">Rp 35K</p>
                                    <span
                                        class="text-xs bg-primary/10 text-primary font-semibold px-2 py-0.5 rounded-full">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-bg px-4 py-3">
                                <div>
                                    <p class="font-bold text-sm text-dark">09.00 → 10.30</p>
                                    <p class="text-xs text-grayText mt-0.5">Shuttle Reguler · 4 kursi</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-dark text-sm">Rp 28K</p>
                                    <span
                                        class="text-xs bg-orange-100 text-orange-600 font-semibold px-2 py-0.5 rounded-full">Hampir
                                        Penuh</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom nav --}}
                    <div class="flex border-t border-borderColor mt-4 px-2 py-3 gap-1">
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <i class="fa-solid fa-house text-primary text-sm"></i>
                            <span class="text-[9px] font-bold text-primary">Beranda</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <i class="fa-solid fa-ticket text-grayText text-sm"></i>
                            <span class="text-[9px] font-medium text-grayText">Tiket</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <i class="fa-solid fa-map-location-dot text-grayText text-sm"></i>
                            <span class="text-[9px] font-medium text-grayText">Tracking</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <i class="fa-solid fa-user text-grayText text-sm"></i>
                            <span class="text-[9px] font-medium text-grayText">Profil</span>
                        </div>
                    </div>

                </div>

                {{-- Floating chips --}}
                <div
                    class="animate-float-slow absolute bottom-6 -left-4 z-20 flex items-center gap-3 rounded-2xl bg-white border border-borderColor px-4 py-3 shadow-xl">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark">Pembayaran Sukses!</p>
                        <p class="text-[10px] text-grayText">Tiket dikirim ke emailmu</p>
                    </div>
                </div>

                <div class="animate-float absolute top-8 -right-4 z-20 flex items-center gap-3 rounded-2xl bg-white border border-borderColor px-4 py-3 shadow-xl"
                    style="animation-delay:2s">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark">Live Tracking</p>
                        <p class="text-[10px] text-grayText">5 menit lagi tiba</p>
                    </div>
                </div>

            </div>

        </div>

    </section>

    {{-- ========== TRUST MARQUEE ========== --}}
    <div class="border-y border-borderColor bg-white py-5 overflow-hidden">
        <div class="overflow-hidden">
            <div class="marquee-track">
                @php
                    $trustItems = [
                        ['icon' => 'fa-star text-yellow-400', 'text' => 'Rated 4.9 di Play Store'],
                        ['icon' => 'fa-shield-halved text-green-500', 'text' => 'Pembayaran 100% Aman'],
                        ['icon' => 'fa-headset text-primary', 'text' => 'Support 24/7'],
                        ['icon' => 'fa-bolt text-accent', 'text' => 'Booking dalam 30 Detik'],
                        ['icon' => 'fa-route text-blue-500', 'text' => '50+ Rute Aktif'],
                        ['icon' => 'fa-users text-purple-500', 'text' => '10.000+ Pengguna Puas'],
                    ];
                @endphp
                @foreach ([1, 2] as $loop)
                    <div class="flex items-center gap-12 px-6 shrink-0">
                        @foreach ($trustItems as $item)
                            <span class="text-sm font-bold text-grayText/60 whitespace-nowrap flex items-center gap-2">
                                <i class="fa-solid {{ $item['icon'] }}"></i>
                                {{ $item['text'] }}
                            </span>
                            <span class="text-grayText/30">·</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== FITUR ========== --}}
    <section id="fitur" class="border-t border-borderColor bg-white px-6 py-24 lg:px-12">

        <div class="mx-auto max-w-7xl">

            <div class="section-label">Fitur Unggulan</div>
            <h2 class="font-fraunces text-4xl font-black leading-tight text-dark max-w-xl">
                Semua yang kamu butuhkan, dalam satu genggaman
            </h2>

            {{-- Main 3 features --}}
            <div class="mt-16 grid gap-5 lg:grid-cols-3">

                {{-- Booking Instan --}}
                <div class="card-hover rounded-3xl border border-borderColor bg-bg p-8 flex flex-col gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 mb-2">
                        <i class="fa-solid fa-ticket text-xl text-primary"></i>
                    </div>
                    <h3 class="font-fraunces text-2xl font-bold text-dark">Booking Instan</h3>
                    <p class="text-grayText leading-7 text-sm">Pesan shuttle hanya dalam hitungan detik. Pilih rute, jam
                        keberangkatan, dan kursi — tanpa antri, tanpa ribet. Konfirmasi tiket langsung masuk ke HP-mu.</p>
                    <div class="mt-auto pt-4 flex flex-wrap gap-2">
                        <span
                            class="rounded-full border border-borderColor bg-white px-3 py-1 text-xs font-semibold text-grayText">E-Ticket</span>
                        <span
                            class="rounded-full border border-borderColor bg-white px-3 py-1 text-xs font-semibold text-grayText">QR
                            Code</span>
                        <span
                            class="rounded-full border border-borderColor bg-white px-3 py-1 text-xs font-semibold text-grayText">Instant</span>
                    </div>
                </div>

                {{-- Pilih Kursi --}}
                <div class="card-hover rounded-3xl border border-borderColor bg-bg p-8 flex flex-col gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 mb-2">
                        <i class="fa-solid fa-chair text-xl text-primary"></i>
                    </div>
                    <h3 class="font-fraunces text-2xl font-bold text-dark">Pilih Kursi</h3>
                    <p class="text-grayText leading-7 text-sm">Tampilan denah kursi interaktif. Pilih posisi duduk
                        favoritmu sebelum berangkat — window seat, aisle, atau dekat pintu.</p>
                    {{-- Mini seat map --}}
                    <div class="mt-auto grid grid-cols-5 gap-1.5 pt-3">
                        @php
                            $seats = [
                                ['label' => '1A', 'state' => 'available'],
                                ['label' => '1B', 'state' => 'selected'],
                                ['label' => '', 'state' => 'gap'],
                                ['label' => '1C', 'state' => 'available'],
                                ['label' => '1D', 'state' => 'taken'],
                                ['label' => '2A', 'state' => 'taken'],
                                ['label' => '2B', 'state' => 'available'],
                                ['label' => '', 'state' => 'gap'],
                                ['label' => '2C', 'state' => 'taken'],
                                ['label' => '2D', 'state' => 'available'],
                                ['label' => '3A', 'state' => 'selected'],
                                ['label' => '3B', 'state' => 'taken'],
                                ['label' => '', 'state' => 'gap'],
                                ['label' => '3C', 'state' => 'available'],
                                ['label' => '3D', 'state' => 'taken'],
                            ];
                        @endphp
                        @foreach ($seats as $seat)
                            @if ($seat['state'] === 'gap')
                                <div class="h-7"></div>
                            @elseif ($seat['state'] === 'selected')
                                <div
                                    class="h-7 rounded-md bg-primary flex items-center justify-center text-[9px] font-bold text-white">
                                    {{ $seat['label'] }}</div>
                            @elseif ($seat['state'] === 'available')
                                <div
                                    class="h-7 rounded-md bg-primary/15 border border-primary/20 flex items-center justify-center text-[9px] font-bold text-primary">
                                    {{ $seat['label'] }}</div>
                            @else
                                <div
                                    class="h-7 rounded-md bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-400">
                                    {{ $seat['label'] }}</div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex gap-4 text-[10px] text-grayText font-medium">
                        <span class="flex items-center gap-1"><span
                                class="inline-block h-3 w-3 rounded-sm bg-primary"></span>Dipilih</span>
                        <span class="flex items-center gap-1"><span
                                class="inline-block h-3 w-3 rounded-sm bg-gray-200"></span>Terisi</span>
                        <span class="flex items-center gap-1"><span
                                class="inline-block h-3 w-3 rounded-sm bg-primary/15 border border-primary/20"></span>Tersedia</span>
                    </div>
                </div>

                {{-- Live Tracking --}}
                <div class="card-hover rounded-3xl border border-borderColor bg-bg p-8 flex flex-col gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 mb-2">
                        <i class="fa-solid fa-map-location-dot text-xl text-primary"></i>
                    </div>
                    <h3 class="font-fraunces text-2xl font-bold text-dark">Live Tracking</h3>
                    <p class="text-grayText leading-7 text-sm">Pantau posisi shuttle secara real-time di peta. Notifikasi
                        otomatis dikirim saat shuttle 5 menit lagi tiba.</p>
                    {{-- Mini map --}}
                    <div
                        class="mt-auto rounded-2xl bg-gradient-to-br from-green-50 to-teal-50 border border-green-100 p-4 relative overflow-hidden h-28">
                        <div class="absolute inset-0 opacity-20"
                            style="background-image:repeating-linear-gradient(0deg,transparent,transparent 20px,#86efac 20px,#86efac 21px),repeating-linear-gradient(90deg,transparent,transparent 20px,#86efac 20px,#86efac 21px)">
                        </div>
                        <div class="relative flex items-center justify-between h-full">
                            <div class="flex flex-col items-center gap-1">
                                <div class="h-4 w-4 rounded-full bg-blue-500 border-2 border-white shadow-md"></div>
                                <span
                                    class="text-[9px] font-bold text-blue-600 bg-white/80 px-1.5 py-0.5 rounded-full">Karawang</span>
                            </div>
                            <div class="flex-1 mx-2 flex items-center gap-1">
                                <div class="flex-1 h-0.5 bg-primary/40 rounded-full"></div>
                                <div
                                    class="h-7 w-7 rounded-full bg-primary text-white flex items-center justify-center shadow-lg animate-pulse">
                                    <i class="fa-solid fa-bus text-[9px]"></i>
                                </div>
                                <div class="flex-1 h-0.5 bg-gray-200 rounded-full"></div>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <div class="h-4 w-4 rounded-full bg-primary border-2 border-white shadow-md"></div>
                                <span
                                    class="text-[9px] font-bold text-primary bg-white/80 px-1.5 py-0.5 rounded-full">Telkom
                                    Univ</span>
                            </div>
                        </div>
                        <div
                            class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white border border-borderColor rounded-full px-2 py-0.5 text-[9px] font-bold text-dark shadow">
                            5 menit lagi</div>
                    </div>
                </div>

            </div>

            {{-- Extra features row --}}
            <div class="mt-5 grid gap-5 lg:grid-cols-3">
                @php
                    $extras = [
                        [
                            'icon' => 'fa-wallet',
                            'color' => 'orange',
                            'title' => 'Bayar Digital',
                            'desc' => 'Transfer, e-wallet, QRIS — semua metode pembayaran didukung.',
                        ],
                        [
                            'icon' => 'fa-bell',
                            'color' => 'purple',
                            'title' => 'Notifikasi Pintar',
                            'desc' => 'Pengingat keberangkatan otomatis, update posisi, dan konfirmasi tiket.',
                        ],
                        [
                            'icon' => 'fa-history',
                            'color' => 'teal',
                            'title' => 'Riwayat Perjalanan',
                            'desc' => 'Akses semua tiket lama, unduh invoice, dan booking ulang dengan sekali klik.',
                        ],
                    ];
                @endphp
                @foreach ($extras as $extra)
                    <div class="card-hover rounded-3xl border border-borderColor bg-bg p-7 flex items-start gap-5">
                        <div
                            class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-{{ $extra['color'] }}-50">
                            <i class="fa-solid {{ $extra['icon'] }} text-lg text-{{ $extra['color'] }}-500"></i>
                        </div>
                        <div>
                            <h3 class="font-fraunces text-lg font-bold text-dark">{{ $extra['title'] }}</h3>
                            <p class="mt-2 text-sm text-grayText leading-6">{{ $extra['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </section>

    {{-- ========== CARA KERJA ========== --}}
    <section id="cara" class="border-t border-borderColor bg-bg px-6 py-24 lg:px-12">

        <div class="mx-auto max-w-7xl">

            <div class="section-label">Cara Kerja</div>
            <h2 class="font-fraunces text-4xl font-black leading-tight text-dark max-w-xl">
                Tiga langkah mudah untuk berangkat
            </h2>

            <div class="relative mt-16 grid gap-10 lg:grid-cols-3">

                {{-- Step 1 --}}
                <div class="relative text-center">
                    <div class="relative mx-auto mb-6 h-20 w-20">
                        <div
                            class="h-full w-full rounded-full border-2 border-borderColor bg-white flex items-center justify-center">
                            <i class="fa-solid fa-download text-2xl text-primary"></i>
                        </div>
                        <span
                            class="absolute -right-1 -top-1 h-6 w-6 rounded-full bg-primary text-white text-xs font-black flex items-center justify-center">1</span>
                    </div>
                    <h3 class="font-fraunces text-xl font-bold text-dark">Download Aplikasi</h3>
                    <p class="mt-3 text-sm text-grayText leading-7 max-w-xs mx-auto">Install GASSIN gratis dari Google Play
                        Store. Setup akun hanya butuh 1 menit.</p>
                </div>

                {{-- Step 2 --}}
                <div class="relative text-center">
                    <div class="relative mx-auto mb-6 h-20 w-20">
                        <div
                            class="h-full w-full rounded-full border-2 border-borderColor bg-white flex items-center justify-center">
                            <i class="fa-solid fa-magnifying-glass text-2xl text-primary"></i>
                        </div>
                        <span
                            class="absolute -right-1 -top-1 h-6 w-6 rounded-full bg-primary text-white text-xs font-black flex items-center justify-center">2</span>
                    </div>
                    <h3 class="font-fraunces text-xl font-bold text-dark">Cari &amp; Pesan</h3>
                    <p class="mt-3 text-sm text-grayText leading-7 max-w-xs mx-auto">Pilih rute, jadwal, dan kursi. Bayar
                        pakai metode favoritmu — selesai!</p>
                </div>

                {{-- Step 3 --}}
                <div class="relative text-center">
                    <div class="relative mx-auto mb-6 h-20 w-20">
                        <div
                            class="h-full w-full rounded-full border-2 border-borderColor bg-white flex items-center justify-center">
                            <i class="fa-solid fa-bus text-2xl text-primary"></i>
                        </div>
                        <span
                            class="absolute -right-1 -top-1 h-6 w-6 rounded-full bg-primary text-white text-xs font-black flex items-center justify-center">3</span>
                    </div>
                    <h3 class="font-fraunces text-xl font-bold text-dark">Berangkat!</h3>
                    <p class="mt-3 text-sm text-grayText leading-7 max-w-xs mx-auto">Tunjukkan QR tiket ke driver, duduk di
                        kursi yang kamu pilih, dan nikmati perjalanan.</p>
                </div>

            </div>

            <div class="mt-14 flex justify-center">
                <a href="#download" class="btn-primary">
                    <i class="fa-brands fa-google-play"></i>
                    Coba Sekarang — Gratis
                </a>
            </div>

        </div>

    </section>

    {{-- ========== DRIVER ========== --}}
    <section id="driver" class="border-t border-borderColor bg-bg px-6 py-24 lg:px-12">

        {{-- ganti max-w-7xl jadi w-full --}}
        <div class="mx-auto w-full items-center">

            <div class="rounded-[40px] bg-white border border-borderColor p-10 lg:p-16 shadow-sm">

                <div class="grid gap-16 lg:grid-cols-2 items-center">

                    {{-- LEFT --}}
                    <div>

                        <div class="section-label">
                            Bergabung Bersama Kami
                        </div>

                        <h2 class="font-fraunces text-4xl lg:text-5xl font-black leading-tight text-dark">
                            Jadilah driver GASSIN,<br>
                            kendalikan penghasilanmu
                        </h2>

                        <p class="mt-6 max-w-xl leading-8 text-grayText">
                            Bergabunglah dengan ratusan driver GASSIN.
                            Nikmati fleksibilitas jam kerja, penghasilan kompetitif,
                            bonus performa, dan dukungan penuh dari tim kami.
                        </p>

                        <div class="mt-10">
                            <a href="mailto:naltydev@gmail.com" class="btn-primary">
                                <i class="fa-solid fa-envelope"></i>
                                Daftar Jadi Driver
                            </a>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="grid grid-cols-2 gap-5">

                        @php
                            $perks = [
                                [
                                    'icon' => 'fa-money-bill-wave',
                                    'color' => 'green',
                                    'title' => 'Penghasilan Kompetitif',
                                    'desc' => 'Hingga Rp 6 juta/bulan dengan bonus performa',
                                ],
                                [
                                    'icon' => 'fa-clock',
                                    'color' => 'blue',
                                    'title' => 'Jadwal Fleksibel',
                                    'desc' => 'Atur sendiri jadwal kerjamu sesuai kebutuhan',
                                ],
                                [
                                    'icon' => 'fa-headset',
                                    'color' => 'purple',
                                    'title' => 'Support 24/7',
                                    'desc' => 'Tim kami siap bantu kapanpun kamu butuhkan',
                                ],
                                [
                                    'icon' => 'fa-shield-halved',
                                    'color' => 'orange',
                                    'title' => 'Asuransi Driver',
                                    'desc' => 'Proteksi kecelakaan dan kesehatan untuk driver',
                                ],
                            ];
                        @endphp

                        @foreach ($perks as $perk)
                            <div
                                class="rounded-3xl bg-bg border border-borderColor p-6 hover:-translate-y-1 transition-all duration-300">

                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $perk['color'] }}-50">
                                    <i class="fa-solid {{ $perk['icon'] }} text-xl text-{{ $perk['color'] }}-500"></i>
                                </div>

                                <h4 class="mt-5 font-fraunces text-xl font-bold text-dark">
                                    {{ $perk['title'] }}
                                </h4>

                                <p class="mt-3 text-sm leading-7 text-grayText">
                                    {{ $perk['desc'] }}
                                </p>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ========== DOWNLOAD CTA ========== --}}
    <section id="download" class="relative overflow-hidden bg-dark px-6 py-28 text-center lg:px-12">

        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute left-1/2 top-1/2 h-[700px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/15 blur-3xl">
            </div>
            <div class="absolute -right-20 bottom-0 h-[300px] w-[300px] rounded-full bg-orange-500/10 blur-3xl"></div>
            <div class="absolute -left-20 top-0 h-[200px] w-[200px] rounded-full bg-primary/10 blur-2xl"></div>
            <div class="absolute inset-0 dot-pattern opacity-5"></div>
        </div>

        <div class="relative mx-auto max-w-3xl">

            <div
                class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white/70">
                <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                Tersedia di Play Store
            </div>

            <h2 class="font-fraunces text-5xl font-black text-white leading-tight">
                Siap untuk perjalanan<br>yang lebih baik?
            </h2>

            <p class="mt-6 text-lg leading-8 text-white/50">
                Gabung bersama 10.000+ pengguna GASSIN dan rasakan kemudahan booking shuttle. Download sekarang — gratis!
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="https://play.google.com/store/apps/details?id=com.naltylabs.gassin&pcampaignid=web_share" target="_blank" class="btn-primary text-base !px-8 !py-4">
                    <i class="fa-brands fa-google-play text-base"></i>
                    Download di Play Store
                </a>
                <a href="#cara"
                    class="inline-flex items-center gap-2 text-white/60 text-sm font-semibold hover:text-white transition">
                    <i class="fa-solid fa-circle-info"></i>
                    Pelajari lebih lanjut
                </a>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-center gap-6 text-sm text-white/40">
                <span class="flex items-center gap-2"><i class="fa-solid fa-star text-yellow-400"></i> 4.9 rating</span>
                <span>·</span>
                <span>10K+ unduhan</span>
                <span>·</span>
                <span>Gratis selamanya</span>
            </div>

        </div>

    </section>

    {{-- ========== FOOTER ========== --}}
    <footer class="bg-white border-t border-borderColor px-6 py-12 lg:px-12">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-10 lg:grid-cols-4">

                <div class="lg:col-span-2">
                    <span class="font-fraunces text-2xl font-black text-primary">GASSIN</span>
                    <p class="mt-4 text-sm text-grayText leading-7 max-w-sm">Platform booking shuttle modern yang
                        memudahkan perjalananmu. Pesan, pilih kursi, dan pantau posisi — semua dalam satu app.</p>
                    <div class="mt-6 flex gap-3">
                        @foreach (['instagram', 'twitter', 'tiktok'] as $social)
                            <a href="#"
                                class="h-9 w-9 rounded-full border border-borderColor flex items-center justify-center text-grayText hover:text-primary hover:border-primary transition">
                                <i class="fa-brands fa-{{ $social }} text-sm"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-dark text-sm mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm text-grayText">
                        @foreach (['fitur' => 'Fitur', 'cara' => 'Cara Kerja', 'harga' => 'Harga', 'testimoni' => 'Testimoni', 'faq' => 'FAQ'] as $anchor => $label)
                            <li><a href="#{{ $anchor }}"
                                    class="hover:text-primary transition">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-dark text-sm mb-4">Kontak</h4>
                    <ul class="space-y-2.5 text-sm text-grayText">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-envelope text-xs text-primary"></i>
                            naltydevstudio@gmail.com</li>
                        <li class="flex items-center gap-2"><i class="fa-brands fa-whatsapp text-xs text-green-500"></i>
                            +62 89686027339</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-xs text-primary"></i>
                            Jakarta, Indonesia</li>
                    </ul>
                </div>

            </div>

            <div
                class="mt-10 pt-6 border-t border-borderColor flex flex-wrap items-center justify-between gap-4 text-xs text-grayText">
                <p>© {{ date('Y') }} GASSIN Shuttle System. All rights reserved.</p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-primary transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-primary transition">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

@endsection

@push('scripts')
    <script>
        // FAQ accordion
        function toggleFaq(btn) {
            btn.closest('.faq-item').classList.toggle('open');
        }

        // Counter animation
        function animateCounters() {
            document.querySelectorAll('.counter').forEach(el => {
                const target = parseInt(el.dataset.target);
                let current = 0;
                const step = Math.ceil(target / 40);
                const timer = setInterval(() => {
                    current = Math.min(current + step, target);
                    el.textContent = current;
                    if (current >= target) clearInterval(timer);
                }, 40);
            });
        }

        // Trigger counters on scroll
        const firstCounter = document.querySelector('.counter');
        if (firstCounter) {
            new IntersectionObserver((entries, obs) => {
                if (entries[0].isIntersecting) {
                    animateCounters();
                    obs.disconnect();
                }
            }, {
                threshold: 0.5
            }).observe(firstCounter.closest('div'));
        }

        // Animate stat bars from 0 on scroll
        document.querySelectorAll('.stat-bar-fill').forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0';
            new IntersectionObserver((entries, obs) => {
                if (entries[0].isIntersecting) {
                    setTimeout(() => {
                        bar.style.width = targetWidth;
                    }, 200);
                    obs.disconnect();
                }
            }, {
                threshold: 0.2
            }).observe(bar);
        });

        // Active nav highlight on scroll
        window.addEventListener('scroll', () => {
            const pos = window.scrollY + 100;
            document.querySelectorAll('section[id]').forEach(sec => {
                if (pos >= sec.offsetTop && pos < sec.offsetTop + sec.offsetHeight) {
                    document.querySelectorAll('nav a[href^="#"]').forEach(l => l.classList.remove(
                        'text-primary'));
                    const active = document.querySelector(`nav a[href="#${sec.id}"]`);
                    if (active) active.classList.add('text-primary');
                }
            });
        });
    </script>
@endpush
