{{-- pages/drivers/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Driver Detail
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Detail informasi driver.
                </p>
            </div>

            <div class="flex items-center gap-3">

                <a href="{{ route('drivers.edit', $driver->id) }}"
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl transition">
                    Edit
                </a>

                <a href="{{ route('drivers.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-100 transition">
                    Back
                </a>

            </div>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl shadow p-6">

                <div class="flex flex-col items-center text-center">

                    <div
                        class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-600">
                        {{ strtoupper(substr($driver->user->name, 0, 1)) }}
                    </div>

                    <h2 class="mt-4 text-xl font-semibold text-gray-800">
                        {{ $driver->user->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        Driver
                    </p>

                    <div class="mt-4">

                        <span class="px-4 py-1 rounded-full text-sm bg-green-100 text-green-700">
                            Verified
                        </span>

                    </div>

                </div>

                <div class="mt-8 space-y-5">

                    <div>
                        <p class="text-sm text-gray-500">
                            Email
                        </p>

                        <p class="font-medium text-gray-800">
                            {{ $driver->user->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Phone Number
                        </p>

                        <p class="font-medium text-gray-800">
                            {{ $driver->phone }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Driver Status
                        </p>

                        <p class="font-medium text-gray-800 capitalize">
                            {{ $driver->status }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Joined At
                        </p>

                        <p class="font-medium text-gray-800">
                            {{ $driver->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                </div>

            </div>

            <div class="xl:col-span-2 bg-white rounded-2xl shadow p-6">

                <div class="mb-6">

                    <h2 class="text-xl font-semibold text-gray-800">
                        Driver Documents
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Dokumen identitas driver.
                    </p>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    @foreach ($driver->documents as $document)
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">

                            <div class="aspect-square bg-gray-100">

                                <img src="{{ asset('storage/' . $document->file_path) }}"
                                    class="w-full h-full object-cover">

                            </div>

                            <div class="p-4">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <h3 class="font-semibold text-gray-800 uppercase">
                                            {{ $document->type }}
                                        </h3>

                                        <p class="text-sm text-green-600">
                                            Approved
                                        </p>

                                    </div>

                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                        class="text-sm text-blue-600 hover:underline">
                                        View
                                    </a>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </div>
@endsection
