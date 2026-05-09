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

                <a
                    href="{{ route('driver.documents') }}"
                    class="inline-flex items-center justify-center bg-primary text-white px-5 py-3 rounded-xl font-medium hover:opacity-90 transition"
                >
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

                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($status === 'approved')
                        bg-green-100 text-green-700
                    @elseif($status === 'rejected')
                        bg-red-100 text-red-700
                    @else
                        bg-yellow-100 text-yellow-700
                    @endif
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

                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($document->status === 'approved')
                                    bg-green-100 text-green-700
                                @elseif($document->status === 'rejected')
                                    bg-red-100 text-red-700
                                @else
                                    bg-yellow-100 text-yellow-700
                                @endif
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

        </div>

    </div>

</div>

@endsection