@extends('layouts.app-driver')

@section('content')
    <div class="max-w-5xl mx-auto">

        <div class="grid md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow p-8">

                <div class="mb-6">

                    <h2 class="text-3xl font-bold">
                        Welcome Driver
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Kelola akun dan verifikasi driver Anda
                    </p>

                </div>

                <div class="border rounded-2xl p-6">

                    <div class="mb-4">

                        <h3 class="text-xl font-semibold">
                            Driver Verification
                        </h3>

                        <p class="text-gray-500 text-sm mt-2">
                            Upload dokumen seperti KTP, SIM, dan selfie untuk proses verifikasi akun driver.
                        </p>

                    </div>

                    <a href="{{ route('driver.documents') }}"
                        class="inline-flex items-center justify-center bg-primary text-white px-5 py-3 rounded-xl font-medium hover:opacity-90 transition">
                        Upload Documents
                    </a>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow p-8">

                <div class="flex items-center justify-between mb-6">

                    <div>

                        <h3 class="text-2xl font-bold">
                            Verification Status
                        </h3>

                        <p class="text-gray-500 text-sm mt-1">
                            Status verifikasi akun driver Anda
                        </p>

                    </div>

                    @php
                        $status = $driver?->verification_status;
                    @endphp

                    <span
                        class="px-4 py-2 rounded-full text-sm font-medium
                    @if ($status === 'approved') bg-green-100 text-green-700
                    @elseif($status === 'rejected')
                        bg-red-100 text-red-700
                    @else
                        bg-yellow-100 text-yellow-700 @endif
                ">
                        {{ ucfirst($status ?? 'pending') }}
                    </span>

                </div>

                <div class="space-y-4">

                    @forelse($driver?->documents ?? [] as $document)
                        <div class="border rounded-xl p-4">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h4 class="font-semibold uppercase">
                                        {{ $document->type }}
                                    </h4>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $document->created_at->format('d M Y H:i') }}
                                    </p>

                                </div>

                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium
                                @if ($document->status === 'approved') bg-green-100 text-green-700
                                @elseif($document->status === 'rejected')
                                    bg-red-100 text-red-700
                                @else
                                    bg-yellow-100 text-yellow-700 @endif
                            ">
                                    {{ ucfirst($document->status) }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-gray-400 text-sm">
                            Belum ada dokumen yang diupload
                        </div>
                    @endforelse

                </div>
                @if ($isVerified)
                    <div class="mt-8 bg-green-50 border border-green-200 rounded-2xl p-6">

                        <div class="flex items-start gap-4">

                            <div
                                class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                            <div class="flex-1">

                                <h3 class="text-2xl font-bold text-green-700">
                                    Verifikasi Berhasil
                                </h3>

                                <p class="text-green-600 mt-2 leading-relaxed">
                                    Selamat! Semua dokumen Anda telah disetujui. Anda sekarang sudah bisa menggunakan
                                    aplikasi driver Gassin!.
                                </p>

                                <a href="#"
                                    class="inline-flex items-center gap-2 mt-5 bg-green-600 text-white px-5 py-3 rounded-xl font-medium hover:opacity-90 transition">
                                    <i class="fa-solid fa-download"></i>
                                    Download Aplikasi Driver
                                </a>

                            </div>

                        </div>

                    </div>
                @else
                    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-2xl p-6">

                        <div class="flex items-start gap-4">

                            <div
                                class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-clock"></i>
                            </div>

                            <div>

                                <h3 class="text-2xl font-bold text-yellow-700">
                                    Menunggu Verifikasi
                                </h3>

                                <p class="text-yellow-600 mt-2 leading-relaxed">
                                    Lengkapi dan tunggu semua dokumen Anda disetujui admin untuk mengakses aplikasi driver.
                                </p>

                            </div>

                        </div>

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
