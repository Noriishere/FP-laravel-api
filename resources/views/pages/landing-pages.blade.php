@extends('layouts.guest')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #E82C2C;
        --primary-dark: #C41F1F;
        --primary-light: #FF4B4B;
        --accent: #FF8A00;
        --bg: #F9F7F4;
        --dark: #111010;
        --gray: #6B6B6B;
        --card-bg: #FFFFFF;
        --border: #E8E3DC;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg);
        color: var(--dark);
        overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 60px;
        height: 70px;
        background: rgba(249, 247, 244, 0.88);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
    }

    .nav-logo {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -0.5px;
    }

    .nav-links {
        display: flex;
        gap: 36px;
    }

    .nav-links a {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray);
        text-decoration: none;
        letter-spacing: 0.02em;
        transition: color 0.2s;
    }

    .nav-links a:hover { color: var(--primary); }

    .nav-cta {
        background: var(--primary);
        color: #fff;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }

    .nav-cta:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* ── HERO ── */
    .hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        min-height: calc(100vh - 70px);
        padding: 80px 60px;
        gap: 60px;
        position: relative;
        overflow: hidden;
    }

    /* decorative blobs */
    .hero::before {
        content: '';
        position: absolute;
        width: 520px; height: 520px;
        background: radial-gradient(circle, rgba(232,44,44,0.10) 0%, transparent 70%);
        top: -100px; right: -80px;
        pointer-events: none;
    }

    .hero::after {
        content: '';
        position: absolute;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(255,138,0,0.08) 0%, transparent 70%);
        bottom: 60px; left: 40px;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(232,44,44,0.08);
        border: 1px solid rgba(232,44,44,0.2);
        color: var(--primary);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 99px;
        margin-bottom: 28px;
    }

    .hero-badge span {
        width: 7px; height: 7px;
        background: var(--primary);
        border-radius: 50%;
        animation: pulse 1.6s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.7); }
    }

    .hero-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(2.6rem, 4.5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 24px;
        color: var(--dark);
    }

    .hero-title .highlight {
        color: var(--primary);
        position: relative;
        display: inline-block;
    }

    .hero-title .highlight::after {
        content: '';
        position: absolute;
        left: 0; bottom: 4px;
        width: 100%; height: 6px;
        background: rgba(232,44,44,0.15);
        border-radius: 3px;
        z-index: -1;
    }

    .hero-desc {
        font-size: 1.05rem;
        color: var(--gray);
        line-height: 1.75;
        max-width: 480px;
        margin-bottom: 40px;
    }

    .hero-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary);
        color: #fff;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 6px 20px rgba(232,44,44,0.30);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(232,44,44,0.38);
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid var(--border);
        color: var(--dark);
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: border-color 0.2s, background 0.2s, transform 0.15s;
        background: #fff;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .hero-stats {
        display: flex;
        gap: 36px;
        margin-top: 52px;
        padding-top: 36px;
        border-top: 1px solid var(--border);
    }

    .stat-item { display: flex; flex-direction: column; gap: 4px; }

    .stat-num {
        font-family: 'Syne', sans-serif;
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--dark);
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: 0.78rem;
        color: var(--gray);
        font-weight: 500;
    }

    /* ── HERO IMAGE SIDE ── */
    .hero-image-wrap {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image-card {
        position: relative;
        width: 100%;
        max-width: 520px;
        aspect-ratio: 4/3;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(135deg, #f0ede8 0%, #e8e3dc 100%);
        border: 1px solid var(--border);
        box-shadow: 0 32px 80px rgba(0,0,0,0.12), 0 8px 24px rgba(0,0,0,0.06);
    }

    /* ────────────────────────────────────────────
       REPLACE THIS IMG with your actual shuttle image.
       Set src to your image path or URL.
       The image fills the entire card.
    ──────────────────────────────────────────── */
    .hero-image-card img.hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Placeholder shown ONLY when no real image is set */
    .hero-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        background: linear-gradient(135deg, #f5f2ed 0%, #ece7e0 100%);
    }

    .hero-img-placeholder i {
        font-size: 5rem;
        color: rgba(232,44,44,0.2);
    }

    .hero-img-placeholder p {
        font-size: 0.85rem;
        color: #aaa;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    /* Floating badge on the card */
    .card-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .card-badge-icon {
        width: 38px; height: 38px;
        background: var(--primary);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1rem;
        flex-shrink: 0;
    }

    .card-badge-text strong {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--dark);
    }

    .card-badge-text span {
        font-size: 0.75rem;
        color: var(--gray);
    }

    /* Small decorative dot grid */
    .dot-grid {
        position: absolute;
        top: -28px; right: -28px;
        width: 110px; height: 110px;
        background-image: radial-gradient(circle, #d0cbc2 1.5px, transparent 1.5px);
        background-size: 14px 14px;
        opacity: 0.6;
        z-index: -1;
    }

    /* ── FEATURES ── */
    .features {
        background: #fff;
        padding: 100px 60px;
        border-top: 1px solid var(--border);
    }

    .section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: 14px;
    }

    .section-label::before {
        content: '';
        display: block;
        width: 22px; height: 2px;
        background: var(--primary);
        border-radius: 2px;
    }

    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        font-weight: 800;
        letter-spacing: -1px;
        color: var(--dark);
        max-width: 480px;
        line-height: 1.2;
        margin-bottom: 60px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .feature-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 36px 32px;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(232,44,44,0.04) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.09);
        border-color: rgba(232,44,44,0.25);
    }

    .feature-card:hover::before { opacity: 1; }

    .feature-icon-wrap {
        width: 56px; height: 56px;
        background: rgba(232,44,44,0.08);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 24px;
        transition: background 0.3s;
    }

    .feature-card:hover .feature-icon-wrap {
        background: rgba(232,44,44,0.14);
    }

    .feature-icon-wrap i {
        color: var(--primary);
        font-size: 1.4rem;
    }

    .feature-card h4 {
        font-family: 'Syne', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .feature-card p {
        font-size: 0.92rem;
        color: var(--gray);
        line-height: 1.7;
    }

    /* ── HOW IT WORKS ── */
    .how {
        padding: 100px 60px;
        background: var(--bg);
        border-top: 1px solid var(--border);
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        position: relative;
        margin-top: 60px;
    }

    .steps-grid::before {
        content: '';
        position: absolute;
        top: 32px;
        left: calc(16.6% + 16px);
        right: calc(16.6% + 16px);
        height: 2px;
        background: linear-gradient(to right, var(--border), var(--primary), var(--border));
        opacity: 0.5;
    }

    .step-item {
        text-align: center;
        padding: 0 28px;
    }

    .step-num {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
        transition: border-color 0.3s, background 0.3s;
        position: relative;
        z-index: 1;
    }

    .step-num i {
        font-size: 1.4rem;
        color: var(--primary);
    }

    .step-item:hover .step-num {
        border-color: var(--primary);
        background: rgba(232,44,44,0.06);
    }

    .step-item h4 {
        font-family: 'Syne', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .step-item p {
        font-size: 0.9rem;
        color: var(--gray);
        line-height: 1.65;
    }

    /* ── CTA ── */
    .cta {
        background: var(--dark);
        padding: 100px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232,44,44,0.18) 0%, transparent 65%);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
    }

    .cta h3 {
        font-family: 'Syne', sans-serif;
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        letter-spacing: -1px;
        margin-bottom: 16px;
        position: relative;
    }

    .cta p {
        color: rgba(255,255,255,0.55);
        font-size: 1rem;
        margin-bottom: 40px;
        position: relative;
    }

    .btn-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary);
        color: #fff;
        padding: 16px 36px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 8px 28px rgba(232,44,44,0.4);
        position: relative;
    }

    .btn-cta:hover {
        background: var(--primary-light);
        transform: translateY(-3px);
        box-shadow: 0 14px 40px rgba(232,44,44,0.5);
    }

    /* ── FOOTER ── */
    footer {
        padding: 28px 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--border);
        background: #fff;
    }

    footer .footer-logo {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--primary);
        font-size: 1.1rem;
    }

    footer p { font-size: 0.82rem; color: var(--gray); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        nav { padding: 0 24px; }
        .nav-links { display: none; }

        .hero {
            grid-template-columns: 1fr;
            padding: 60px 24px;
            text-align: center;
            min-height: auto;
        }

        .hero-desc { margin: 0 auto 36px; }
        .hero-actions { justify-content: center; }
        .hero-stats { justify-content: center; }

        .features, .how, .cta { padding: 72px 24px; }

        .features-grid, .steps-grid { grid-template-columns: 1fr; gap: 20px; }

        .steps-grid::before { display: none; }

        footer { flex-direction: column; gap: 8px; text-align: center; padding: 24px; }
    }
</style>

{{-- NAV --}}
<nav>
    <span class="nav-logo">GASSIN</span>

    <div class="nav-links">
        <a href="#fitur">Fitur</a>
        <a href="#cara">Cara Kerja</a>
        <a href="#download">Download</a>
    </div>

    <a href="#" class="nav-cta">
        <i class="fa-brands fa-google-play"></i>
        Download App
    </a>
</nav>

{{-- HERO --}}
<section class="hero">
    <div>
        <div class="hero-badge">
            <span></span>
            Shuttle Booking Modern
        </div>

        <h1 class="hero-title">
            Shuttle Lebih<br>
            <span class="highlight">Cepat &amp; Praktis</span>
        </h1>

        <p class="hero-desc">
            GASSIN adalah aplikasi booking shuttle modern yang memudahkan kamu memesan perjalanan,
            memilih kursi, dan tracking posisi shuttle langsung dari smartphone.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-primary">
                <i class="fa-brands fa-google-play"></i>
                Download di Play Store
            </a>
            <a href="#fitur" class="btn-outline">
                <i class="fa-solid fa-circle-play"></i>
                Lihat Fitur
            </a>
        </div>

        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-num">10K+</span>
                <span class="stat-label">Pengguna Aktif</span>
            </div>
            <div class="stat-item">
                <span class="stat-num">98%</span>
                <span class="stat-label">Kepuasan</span>
            </div>
            <div class="stat-item">
                <span class="stat-num">50+</span>
                <span class="stat-label">Rute Tersedia</span>
            </div>
        </div>
    </div>

    {{-- IMAGE SIDE --}}
    <div class="hero-image-wrap">
        <div class="dot-grid"></div>

        <div class="hero-image-card">

            {{--
                GANTI src="#" DENGAN PATH GAMBAR ANDA
                Contoh: src="{{ asset('images/shuttle-hero.jpg') }}"
                Atau URL eksternal: src="https://example.com/bus.jpg"
            --}}
            <img
                class="hero-img"
                src="#"
                alt="GASSIN Shuttle"
                style="display:none"
                onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
            >

            {{-- Placeholder — otomatis hilang saat gambar berhasil load --}}
            <div class="hero-img-placeholder">
                <i class="fa-solid fa-bus"></i>
                <p>GANTI SRC DENGAN GAMBAR ANDA</p>
            </div>

        </div>

        {{-- Floating info badge --}}
        <div class="card-badge">
            <div class="card-badge-icon">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div class="card-badge-text">
                <strong>Live Tracking</strong>
                <span>Pantau posisi real-time</span>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="fitur" class="features">
    <div class="section-label">Fitur Unggulan</div>
    <h2 class="section-title">Semua yang kamu butuhkan, dalam satu genggaman</h2>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon-wrap">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <h4>Booking Instan</h4>
            <p>Pesan shuttle hanya dalam hitungan detik. Tanpa antrian, tanpa ribet.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon-wrap">
                <i class="fa-solid fa-chair"></i>
            </div>
            <h4>Pilih Kursi</h4>
            <p>Pilih posisi duduk favoritmu sesuai kenyamanan dan kebutuhan perjalanan.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon-wrap">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <h4>Live Tracking</h4>
            <p>Pantau posisi shuttle secara real-time. Tahu kapan shuttle tiba, tanpa tebak-tebakan.</p>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section id="cara" class="how">
    <div class="section-label">Cara Kerja</div>
    <h2 class="section-title">Tiga langkah mudah untuk berangkat</h2>

    <div class="steps-grid">
        <div class="step-item">
            <div class="step-num">
                <i class="fa-solid fa-download"></i>
            </div>
            <h4>Download Aplikasi</h4>
            <p>Install GASSIN gratis dari Google Play Store di smartphone kamu.</p>
        </div>

        <div class="step-item">
            <div class="step-num">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4>Cari Jadwal</h4>
            <p>Pilih rute dan jadwal keberangkatan yang sesuai dengan rencanamu.</p>
        </div>

        <div class="step-item">
            <div class="step-num">
                <i class="fa-solid fa-bus"></i>
            </div>
            <h4>Berangkat!</h4>
            <p>Scan tiket digital dan nikmati perjalanan shuttle yang nyaman.</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section id="download" class="cta">
    <h3>Siap untuk perjalanan lebih baik?</h3>
    <p>Gabung bersama ribuan pengguna GASSIN dan rasakan kemudahan booking shuttle.</p>

    <a href="#" class="btn-cta">
        <i class="fa-brands fa-google-play"></i>
        Download Sekarang — Gratis
    </a>
</section>

{{-- FOOTER --}}
<footer>
    <span class="footer-logo">GASSIN</span>
    <p>© 2026 GASSIN Shuttle System. All rights reserved.</p>
</footer>

@endsection