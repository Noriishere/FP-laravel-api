@extends('layouts.guest')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,700&display=swap" rel="stylesheet">

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
        font-family: 'Fraunces', serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--primary);
        letter-spacing: 0px;
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: clamp(2.4rem, 4vw, 3.4rem);
        font-weight: 900;
        line-height: 1.12;
        letter-spacing: -0.5px;
        margin-bottom: 24px;
        color: var(--dark);
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: 1.65rem;
        font-weight: 900;
        color: var(--dark);
        letter-spacing: -0.5px;
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: clamp(1.7rem, 2.8vw, 2.4rem);
        font-weight: 900;
        letter-spacing: -0.3px;
        color: var(--dark);
        max-width: 480px;
        line-height: 1.2;
        margin-bottom: 60px;
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: 1.12rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
        letter-spacing: 0;
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
        position: relative;
        font-optical-sizing: auto;
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
        font-family: 'Fraunces', serif;
        font-weight: 900;
        color: var(--primary);
        font-size: 1.1rem;
        font-optical-sizing: auto;
    }

    footer p { font-size: 0.82rem; color: var(--gray); }

    /* ── DRIVER SECTION ── */
    .driver {
        padding: 100px 60px;
        background: #fff;
        border-top: 1px solid var(--border);
    }

    .driver-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .driver-perks {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 40px;
    }

    .perk-item {
        display: flex;
        gap: 18px;
        align-items: flex-start;
        padding: 20px 22px;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: var(--bg);
        transition: border-color 0.25s, transform 0.2s, box-shadow 0.25s;
    }

    .perk-item:hover {
        border-color: rgba(232,44,44,0.3);
        transform: translateX(6px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.06);
    }

    .perk-icon {
        width: 44px; height: 44px;
        flex-shrink: 0;
        border-radius: 11px;
        background: rgba(232,44,44,0.08);
        display: flex; align-items: center; justify-content: center;
    }

    .perk-icon i { color: var(--primary); font-size: 1.1rem; }

    .perk-text strong {
        display: block;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .perk-text span {
        font-size: 0.85rem;
        color: var(--gray);
        line-height: 1.6;
    }

    .driver-visual {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .driver-card-main {
        background: var(--dark);
        border-radius: 24px;
        padding: 48px 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .driver-card-main::before {
        content: '';
        position: absolute;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232,44,44,0.22) 0%, transparent 65%);
        top: -60px; right: -60px;
        pointer-events: none;
    }

    .driver-card-main i.card-icon {
        font-size: 3.5rem;
        color: rgba(255,255,255,0.15);
        position: absolute;
        bottom: 20px; right: 30px;
    }

    .driver-card-main .earnings-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.45);
        margin-bottom: 10px;
    }

    .driver-card-main .earnings-num {
        font-family: 'Fraunces', serif;
        font-size: 2.8rem;
        font-weight: 900;
        color: #fff;
        line-height: 1;
        margin-bottom: 6px;
        font-optical-sizing: auto;
    }

    .driver-card-main .earnings-sub {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.45);
    }

    .driver-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 28px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 99px;
        background: rgba(255,255,255,0.08);
        font-size: 0.78rem;
        font-weight: 600;
        color: rgba(255,255,255,0.75);
    }

    .chip i { color: var(--primary); font-size: 0.7rem; }

    .driver-card-sub {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .sub-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: border-color 0.2s;
    }

    .sub-card:hover { border-color: rgba(232,44,44,0.3); }

    .sub-card i {
        font-size: 1.3rem;
        color: var(--primary);
        margin-bottom: 10px;
        display: block;
    }

    .sub-card strong {
        display: block;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .sub-card span {
        font-size: 0.78rem;
        color: var(--gray);
    }

    .btn-driver {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--dark);
        color: #fff;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        margin-top: 40px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .btn-driver:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.22);
    }

    .btn-driver-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid var(--border);
        color: var(--gray);
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        margin-top: 40px;
        margin-left: 12px;
        transition: border-color 0.2s, color 0.2s, transform 0.15s;
        background: #fff;
    }

    .btn-driver-outline:hover {
        border-color: var(--dark);
        color: var(--dark);
        transform: translateY(-2px);
    }

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

        .driver-inner { grid-template-columns: 1fr; gap: 48px; }
        .driver-card-sub { grid-template-columns: 1fr 1fr; }
        .driver { padding: 72px 24px; }
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
        <a href="#driver">Jadi Driver</a>
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

{{-- DRIVER SECTION --}}
<section id="driver" class="driver">
    <div class="driver-inner">

        {{-- LEFT: copy --}}
        <div>
            <div class="section-label">Bergabung Bersama Kami</div>
            <h2 class="section-title">Jadilah driver GASSIN,<br>kendalikan penghasilanmu</h2>

            <p style="font-size:0.97rem;color:var(--gray);line-height:1.75;max-width:460px;">
                Bergabunglah dengan ribuan driver GASSIN dan nikmati fleksibilitas kerja,
                penghasilan kompetitif, serta dukungan penuh dari tim kami untuk perjalanan karir yang lebih baik.
            </p>

            <div class="driver-perks">
                <div class="perk-item">
                    <div class="perk-icon"><i class="fa-solid fa-wallet"></i></div>
                    <div class="perk-text">
                        <strong>Penghasilan Kompetitif</strong>
                        <span>Dapatkan komisi yang transparan dan dibayarkan tepat waktu setiap minggu.</span>
                    </div>
                </div>
                <div class="perk-item">
                    <div class="perk-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="perk-text">
                        <strong>Jam Kerja Fleksibel</strong>
                        <span>Pilih jadwal yang sesuai dengan kebutuhanmu — pagi, siang, atau malam.</span>
                    </div>
                </div>
                <div class="perk-item">
                    <div class="perk-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="perk-text">
                        <strong>Dukungan 24/7</strong>
                        <span>Tim support kami siap membantu kamu kapanpun dibutuhkan di jalan.</span>
                    </div>
                </div>
            </div>

            <div>
                <a href="https://gassin.naltylabs.my.id/driver/" target="_blank" class="btn-driver">
                    <i class="fa-solid fa-steering-wheel"></i>
                    Daftar Jadi Driver
                </a>
                <a href="https://gassin.naltylabs.my.id/driver/" target="_blank" class="btn-driver-outline">
                    Pelajari Lebih Lanjut
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- RIGHT: visual cards --}}
        <div class="driver-visual">
            <div class="driver-card-main">
                <p class="earnings-label">Estimasi Penghasilan / Bulan</p>
                <p class="earnings-num">Rp 6 Juta+</p>
                <p class="earnings-sub">rata-rata driver aktif GASSIN</p>

                <div class="driver-chips">
                    <span class="chip"><i class="fa-solid fa-circle-check"></i> Komisi Transparan</span>
                    <span class="chip"><i class="fa-solid fa-circle-check"></i> Bonus Performa</span>
                    <span class="chip"><i class="fa-solid fa-circle-check"></i> Cair Mingguan</span>
                </div>

                <i class="fa-solid fa-bus card-icon"></i>
            </div>

            <div class="driver-card-sub">
                <div class="sub-card">
                    <i class="fa-solid fa-route"></i>
                    <strong>50+ Rute</strong>
                    <span>Pilih rute yang dekat dengan domisilimu</span>
                </div>
                <div class="sub-card">
                    <i class="fa-solid fa-users"></i>
                    <strong>500+ Driver</strong>
                    <span>Bergabung bersama komunitas driver GASSIN</span>
                </div>
            </div>
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