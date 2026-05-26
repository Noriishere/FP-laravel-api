<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Email | Gassin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:wght@700&display=swap"
        rel="stylesheet"
    />

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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-dark via-[#1B1717] to-black flex items-center justify-center overflow-hidden relative px-6 py-10">

    <!-- Background Glow -->
    <div class="absolute top-[-100px] left-[-100px] w-80 h-80 bg-primary/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-[-100px] right-[-100px] w-80 h-80 bg-accent/20 blur-3xl rounded-full"></div>

    <!-- Card -->
    <div class="glass-card relative z-10 w-full max-w-md bg-white/5 border border-white/10 rounded-[32px] shadow-2xl p-8 text-center">

        @if($status === 'success')

            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-primary/20 flex items-center justify-center shadow-lg shadow-primary/20">
                <i class="fa-solid fa-circle-check text-5xl text-primaryLight"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-white leading-tight mb-3">
                Email Berhasil Diverifikasi
            </h1>

            <!-- Desc -->
            <p class="text-slate-300 leading-relaxed mb-8 text-[15px]">
                Akun kamu sudah aktif dan siap digunakan.
                Sekarang kamu bisa login dan mulai menggunakan aplikasi shuttle system.
            </p>

            <!-- Button -->
            <a
                href="gassin://home"
                class="inline-flex items-center justify-center gap-3 bg-primary hover:bg-primaryDark active:scale-[0.98] transition-all duration-300 text-white font-semibold px-6 py-3 rounded-2xl shadow-xl shadow-primary/30"
            >
                <i class="fa-solid fa-mobile-screen-button"></i>
                Buka Aplikasi
            </a>

        @elseif($status === 'already')

            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-accent/20 flex items-center justify-center shadow-lg shadow-accent/20">
                <i class="fa-solid fa-circle-info text-5xl text-accent"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-white leading-tight mb-3">
                Email Sudah Diverifikasi
            </h1>

            <!-- Desc -->
            <p class="text-slate-300 leading-relaxed mb-8 text-[15px]">
                Email kamu sebelumnya sudah berhasil diverifikasi.
                Kamu bisa langsung masuk ke aplikasi Gassin!.
            </p>

        @else

            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-red-500/20 flex items-center justify-center shadow-lg shadow-red-500/20">
                <i class="fa-solid fa-circle-xmark text-5xl text-red-400"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-white leading-tight mb-3">
                Verifikasi Gagal
            </h1>

            <!-- Desc -->
            <p class="text-slate-300 leading-relaxed mb-8 text-[15px]">
                Link verifikasi tidak valid,
                sudah kadaluarsa,
                atau sudah pernah digunakan sebelumnya.
            </p>
        @endif

    </div>

</body>
</html>