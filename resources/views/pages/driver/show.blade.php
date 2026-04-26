@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <div class="flex justify-between items-start mb-4">
            <h3 class="text-base font-semibold text-gray-800">
                Detail Dokumen
            </h3>

            {{-- STATUS BADGE --}}
            <span class="text-xs px-3 py-1 rounded-full
                {{ $document->status == 'approved' ? 'bg-green-100 text-green-600' : '' }}
                {{ $document->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
                {{ $document->status == 'rejected' ? 'bg-red-100 text-red-600' : '' }}">
                {{ ucfirst($document->status) }}
            </span>
        </div>

        {{-- INFO --}}
        <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium text-gray-700">Driver:</span> {{ $document->driver->user->name }}</p>
            <p><span class="font-medium text-gray-700">Tipe:</span> {{ strtoupper($document->type) }}</p>
        </div>

        {{-- IMAGE --}}
        <div class="mt-5">
            <img src="{{ asset('storage/' . $document->file_path) }}" alt="Document Image"
                 class="w-full rounded-xl border object-cover max-h-[400px]">
        </div>

        {{-- NOTE (kalau reject) --}}
        @if($document->status === 'rejected' && $document->note)
            <div class="mt-4 bg-red-50 text-red-600 text-sm p-3 rounded-lg">
                <b>Alasan:</b> {{ $document->note }}
            </div>
        @endif

    </div>

    {{-- ACTION --}}
    @if ($document->status === 'pending')
        <div class="bg-white rounded-2xl shadow-sm border p-6 space-y-4">

            <h4 class="text-sm font-semibold text-gray-700">
                Aksi Verifikasi
            </h4>

            {{-- APPROVE --}}
            <form method="POST" action="{{ route('driver-documents.approve', $document->id) }}">
                @csrf
                <button 
                    class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm transition">
                    <i class="fa-solid fa-check"></i>
                    Approve
                </button>
            </form>

            {{-- REJECT --}}
            <form method="POST" action="{{ route('driver-documents.reject', $document->id) }}">
                @csrf

                <textarea name="note" required
                    placeholder="Masukkan alasan penolakan..."
                    class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-red-200"></textarea>

                <button 
                    class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm transition">
                    <i class="fa-solid fa-xmark"></i>
                    Reject
                </button>
            </form>

        </div>
    @endif

</div>

{{-- AUTO BACK --}}
@if (session('success'))
<script>
    setTimeout(() => {
        window.location.href = "{{ route('driver-documents.index') }}";
    }, 700);
</script>
@endif

@endsection