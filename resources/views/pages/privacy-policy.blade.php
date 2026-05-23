@extends('layouts.landing-app')

@section('title', 'Privacy Policy — GASSIN')

@section('content')

<section class="relative overflow-hidden border-b border-borderColor hero-gradient">
    <div class="mx-auto max-w-5xl px-6 py-20 lg:px-12">

        <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary">
            Privacy & Data Protection
        </div>

        <h1 class="font-fraunces mt-6 text-5xl font-black leading-tight text-dark">
            Privacy Policy
        </h1>

        <p class="mt-6 max-w-2xl text-base leading-8 text-grayText">
            Kebijakan Privasi ini menjelaskan bagaimana GASSIN mengumpulkan, menggunakan, dan melindungi informasi pengguna saat menggunakan aplikasi shuttle booking kami.
        </p>

        <div class="mt-6 text-sm text-grayText">
            Terakhir diperbarui: 24 Mei 2026
        </div>
    </div>
</section>

<section class="py-16 px-6 lg:px-12">
    <div class="mx-auto max-w-4xl rounded-3xl border border-borderColor bg-white px-6 py-10 shadow-sm lg:px-12">

        <div class="space-y-12 text-[15px] leading-8 text-grayText">

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">1. Pendahuluan</h2>

                <p class="mt-4">
                    Selamat datang di GASSIN.
                </p>

                <p class="mt-4">
                    GASSIN adalah platform pemesanan shuttle modern yang memungkinkan pengguna untuk memesan perjalanan, memilih kursi, melakukan pembayaran digital, dan melacak posisi shuttle secara real-time.
                </p>

                <p class="mt-4">
                    Dengan menggunakan aplikasi GASSIN, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan Kebijakan Privasi ini.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">2. Informasi yang Kami Kumpulkan</h2>

                <ul class="mt-4 list-disc space-y-3 pl-6">
                    <li>Nama pengguna</li>
                    <li>Email dan nomor telepon</li>
                    <li>Riwayat perjalanan dan pemesanan</li>
                    <li>Lokasi pengguna untuk fitur tracking shuttle</li>
                    <li>Informasi perangkat dan log aplikasi</li>
                    <li>Data pembayaran melalui payment gateway pihak ketiga</li>
                </ul>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">3. Penggunaan Informasi</h2>

                <ul class="mt-4 list-disc space-y-3 pl-6">
                    <li>Memproses pemesanan shuttle</li>
                    <li>Mengelola akun pengguna</li>
                    <li>Menyediakan fitur live tracking</li>
                    <li>Mengirim notifikasi perjalanan</li>
                    <li>Meningkatkan keamanan dan performa aplikasi</li>
                </ul>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">4. Keamanan Data</h2>

                <p class="mt-4">
                    Kami berkomitmen melindungi data pengguna menggunakan langkah keamanan yang wajar, termasuk koneksi terenkripsi dan perlindungan akses data.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">5. Hak Pengguna</h2>

                <p class="mt-4">
                    Pengguna dapat meminta penghapusan akun dan data pribadi melalui kontak resmi GASSIN.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">6. Kontak</h2>

                <p class="mt-4">
                    Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi:
                </p>

                <div class="mt-4 rounded-2xl bg-bg border border-borderColor p-5">
                    <p><strong>Email:</strong> naltydev@gmail.com</p>
                    <p><strong>Aplikasi:</strong> GASSIN Shuttle System</p>
                    <p><strong>Lokasi:</strong> Indonesia</p>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection
<a href="{{ url('/privacy-policy') }}" class="hover:text-primary transition">
    Kebijakan Privasi
</a>