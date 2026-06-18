<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GASSIN')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:wght@700;900&display=swap"
        rel="stylesheet">

    <script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E82C2C',
                        primaryDark: '#C41F1F',
                        primaryLight: '#FF4B4B',
                        accent: '#FF8A00',
                        bg: '#F9F7F4',
                        dark: '#111010',
                        grayText: '#6B6B6B',
                        borderColor: '#E8E3DC',
                    },
                    fontFamily: {
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                        fraunces: ['Fraunces', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F9F7F4;
            color: #111010;
            overflow-x: hidden;
        }

        .font-fraunces {
            font-family: 'Fraunces', serif;
        }

        .hero-gradient {
            background: radial-gradient(ellipse 80% 60% at 70% 40%, rgba(232, 44, 44, 0.08) 0%, transparent 70%);
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

        .step-connector {
            position: absolute;
            top: 32px;
            left: calc(50% + 40px);
            width: calc(100% - 80px);
            height: 1px;
            background: linear-gradient(90deg, #E8E3DC 60%, transparent 100%);
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 28s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .badge-glow {
            box-shadow: 0 0 0 4px rgba(232, 44, 44, 0.08);
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

        .testimonial-card {
            background: #fff;
            border: 1px solid #E8E3DC;
            border-radius: 20px;
            padding: 24px;
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

        .dot-pattern {
            background-image: radial-gradient(circle, #d0cbc2 1.5px, transparent 1.5px);
            background-size: 16px 16px;
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

        .counter {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .step-connector {
                display: none;
            }

            .hero-title {
                font-size: 2.8rem;
            }
        }
    </style>
</head>

<body class="bg-bg text-dark font-jakarta overflow-x-hidden">

    @include('layouts.landing-navigation')

    @yield('content')

</body>

</html>
