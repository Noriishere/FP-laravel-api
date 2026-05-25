<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-card {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 flex items-center justify-center p-6 overflow-hidden relative">

    <!-- Background Glow -->
    <div class="absolute w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl top-[-80px] left-[-80px]"></div>
    <div class="absolute w-72 h-72 bg-blue-500/20 rounded-full blur-3xl bottom-[-80px] right-[-80px]"></div>

    <div class="glass-card relative z-10 w-full max-w-md bg-white/10 border border-white/10 shadow-2xl rounded-3xl p-8 text-center">

        @if($status === 'success')

            <div class="w-24 h-24 mx-auto rounded-full bg-emerald-500/20 flex items-center justify-center mb-6">
                <i class="fa-solid fa-circle-check text-5xl text-emerald-400"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-3">
                Email Berhasil Diverifikasi
            </h1>

            <p class="text-slate-300 leading-relaxed mb-8">
                Akun kamu sudah aktif dan siap digunakan.
                Sekarang kamu bisa login dan mulai menggunakan aplikasi shuttle system.
            </p>

            <a
                href="https://gassin.naltylabs.my.id"
                class="inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 transition-all duration-300 text-white font-semibold px-6 py-3 rounded-2xl shadow-lg shadow-emerald-500/30"
            >
                <i class="fa-solid fa-right-to-bracket"></i>
                Masuk ke Aplikasi
            </a>

        @elseif($status === 'already')

            <div class="w-24 h-24 mx-auto rounded-full bg-blue-500/20 flex items-center justify-center mb-6">
                <i class="fa-solid fa-circle-info text-5xl text-blue-400"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-3">
                Email Sudah Diverifikasi
            </h1>

            <p class="text-slate-300 leading-relaxed mb-8">
                Email kamu sebelumnya sudah berhasil diverifikasi.
                Kamu bisa langsung login ke aplikasi.
            </p>

            <a
                href="https://gassin.naltylabs.my.id"
                class="inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 transition-all duration-300 text-white font-semibold px-6 py-3 rounded-2xl shadow-lg shadow-blue-500/30"
            >
                <i class="fa-solid fa-arrow-right"></i>
                Buka Aplikasi
            </a>

        @else

            <div class="w-24 h-24 mx-auto rounded-full bg-red-500/20 flex items-center justify-center mb-6">
                <i class="fa-solid fa-circle-xmark text-5xl text-red-400"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-3">
                Verifikasi Gagal
            </h1>

            <p class="text-slate-300 leading-relaxed mb-8">
                Link verifikasi tidak valid,
                sudah berubah,
                atau sudah kadaluarsa.
            </p>

            <a
                href="https://gassin.naltylabs.my.id"
                class="inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 transition-all duration-300 text-white font-semibold px-6 py-3 rounded-2xl shadow-lg shadow-red-500/30"
            >
                <i class="fa-solid fa-rotate-right"></i>
                Kembali
            </a>

        @endif

    </div>

</body>
</html>