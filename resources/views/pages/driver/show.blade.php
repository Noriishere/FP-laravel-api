@extends('layouts.app')
@section('content')

<div class="max-w-xl mx-auto space-y-4">

    <div class="bg-white p-5 rounded-xl shadow">

        <h3 class="text-sm font-semibold text-gray-700 mb-3">
            Detail Dokumen
        </h3>

        <p><b>Driver:</b> {{ $document->driver->user->name }}</p>
        <p><b>Tipe:</b> {{ strtoupper($document->type) }}</p>
        <p><b>Status:</b> {{ $document->status }}</p>

        {{-- PREVIEW --}}
        <div class="mt-4">
            <img src="{{ asset($document->file_path) }}" 
                 class="w-full rounded-lg border">
        </div>

    </div>

    {{-- ACTION --}}
    @if($document->status === 'pending')

    <div class="bg-white p-5 rounded-xl shadow space-y-3">

        {{-- APPROVE --}}
        <form method="POST" action="{{ route('driver-documents.approve', $document->id) }}">
            @csrf
            <button class="w-full bg-green-600 text-white py-2 rounded-lg">
                Approve
            </button>
        </form>

        {{-- REJECT --}}
        <form method="POST" action="{{ route('driver-documents.reject', $document->id) }}">
            @csrf

            <textarea name="note" placeholder="Alasan penolakan"
                class="w-full border rounded-lg p-2 text-sm"></textarea>

            <button class="w-full bg-red-600 text-white py-2 rounded-lg mt-2">
                Reject
            </button>
        </form>

    </div>

    @endif

</div>

@endsection