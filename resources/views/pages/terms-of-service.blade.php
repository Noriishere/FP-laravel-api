{{-- resources/views/terms-of-service.blade.php --}}

@extends('layouts.landing-app')

@section('title', 'Terms of Service — GASSIN')

@section('content')

<section class="relative overflow-hidden border-b border-borderColor hero-gradient">
    <div class="mx-auto max-w-5xl px-6 py-20 lg:px-12">

        <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary">
            Terms & Conditions
        </div>

        <h1 class="font-fraunces mt-6 text-5xl font-black leading-tight text-dark">
            Terms of Service
        </h1>

        <p class="mt-6 max-w-2xl text-base leading-8 text-grayText">
            Syarat dan Ketentuan ini mengatur penggunaan aplikasi GASSIN oleh seluruh pengguna layanan.
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
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    1. Penerimaan Ketentuan
                </h2>

                <p class="mt-4">
                    Dengan mengakses dan menggunakan aplikasi GASSIN, pengguna dianggap telah membaca,
                    memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    2. Layanan GASSIN
                </h2>

                <p class="mt-4">
                    GASSIN menyediakan layanan pemesanan shuttle, pemilihan kursi,
                    pembayaran digital, dan pelacakan perjalanan secara real-time.
                </p>

                <p class="mt-4">
                    Kami berhak memperbarui, mengubah, atau menghentikan sebagian layanan
                    sewaktu-waktu untuk pengembangan sistem atau alasan operasional.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    3. Akun Pengguna
                </h2>

                <ul class="mt-4 list-disc space-y-3 pl-6">
                    <li>Pengguna bertanggung jawab atas keamanan akun masing-masing.</li>
                    <li>Pengguna wajib memberikan informasi yang valid dan akurat.</li>
                    <li>Segala aktivitas pada akun menjadi tanggung jawab pemilik akun.</li>
                </ul>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    4. Pembayaran
                </h2>

                <p class="mt-4">
                    Semua pembayaran diproses melalui payment gateway pihak ketiga yang terpercaya.
                </p>

                <p class="mt-4">
                    GASSIN tidak menyimpan informasi kartu debit atau kartu kredit pengguna secara langsung.
                </p>

                <p class="mt-4">
                    Pengguna wajib memastikan data pembayaran yang diberikan benar sebelum melakukan transaksi.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    5. Penggunaan yang Dilarang
                </h2>

                <ul class="mt-4 list-disc space-y-3 pl-6">
                    <li>Menyalahgunakan layanan atau sistem aplikasi.</li>
                    <li>Melakukan aktivitas yang merugikan pengguna lain.</li>
                    <li>Mencoba mengakses sistem tanpa izin.</li>
                    <li>Menyebarkan malware, spam, atau aktivitas ilegal lainnya.</li>
                </ul>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    6. Pembatalan dan Refund
                </h2>

                <p class="mt-4">
                    Kebijakan pembatalan dan pengembalian dana dapat berbeda tergantung jenis perjalanan
                    dan kebijakan operator shuttle.
                </p>

                <p class="mt-4">
                    Pengguna disarankan memeriksa detail perjalanan sebelum melakukan pembayaran.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    7. Batasan Tanggung Jawab
                </h2>

                <p class="mt-4">
                    GASSIN berupaya menyediakan layanan terbaik, namun tidak menjamin aplikasi akan selalu
                    bebas gangguan, bug, atau downtime.
                </p>

                <p class="mt-4">
                    GASSIN tidak bertanggung jawab atas kerugian yang timbul akibat penggunaan layanan
                    di luar kendali kami.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    8. Perubahan Ketentuan
                </h2>

                <p class="mt-4">
                    Kami dapat memperbarui Terms of Service sewaktu-waktu.
                    Perubahan akan diumumkan melalui aplikasi atau website resmi GASSIN.
                </p>
            </div>

            <div>
                <h2 class="font-fraunces text-3xl font-black text-dark">
                    9. Kontak
                </h2>

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