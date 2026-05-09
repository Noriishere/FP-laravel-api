@extends('layouts.app-driver')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-8">

        <h2 class="text-3xl font-bold">
            Driver Documents
        </h2>

        <p class="text-gray-500 mt-2">
            Upload dokumen untuk proses verifikasi driver
        </p>

    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 rounded-xl">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow p-6">

            <h3 class="text-xl font-semibold mb-6">
                Upload Document
            </h3>

            <form
                action="{{ route('driver.documents.upload') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5"
            >
                @csrf

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Jenis Dokumen
                    </label>

                    <select
                        name="type"
                        class="w-full border rounded-xl px-4 py-3"
                        required
                    >
                        <option value="">
                            Pilih Dokumen
                        </option>

                        <option value="ktp">
                            KTP
                        </option>

                        <option value="sim">
                            SIM
                        </option>

                        <option value="selfie">
                            Selfie Dengan KTP
                        </option>

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Upload File
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="w-full border rounded-xl px-4 py-3"
                        required
                    >

                    <p class="text-xs text-gray-400 mt-2">
                        JPG, JPEG, PNG max 2MB
                    </p>

                </div>

                <button
                    type="submit"
                    class="w-full bg-primary text-white py-3 rounded-xl font-medium"
                >
                    Upload Document
                </button>

            </form>

        </div>

        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center justify-between mb-6">

                <div>

                    <h3 class="text-xl font-semibold">
                        Verification Status
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Status dokumen driver
                    </p>

                </div>

                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($driver?->verification_status === 'approved')
                        bg-green-100 text-green-700
                    @elseif($driver?->verification_status === 'rejected')
                        bg-red-100 text-red-700
                    @else
                        bg-yellow-100 text-yellow-700
                    @endif
                ">
                    {{ ucfirst($driver?->verification_status ?? 'pending') }}
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

                        <a
                            href="{{ asset('storage/' . $document->file_path) }}"
                            target="_blank"
                            class="inline-block mt-4 text-primary text-sm"
                        >
                            Lihat Dokumen
                        </a>

                    </div>

                @empty

                    <div class="text-gray-400 text-sm">
                        Belum ada dokumen
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection