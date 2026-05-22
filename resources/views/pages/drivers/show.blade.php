{{-- pages/drivers/edit.blade.php --}}

@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Edit Driver
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Update informasi driver dan dokumen.
                </p>
            </div>

            <a href="{{ route('drivers.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-100 transition">
                Back
            </a>

        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">

                <ul class="list-disc pl-5 text-sm space-y-1">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <form action="{{ route('drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl shadow p-6 space-y-5">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Driver Information
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Informasi akun driver.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name
                        </label>

                        <input type="text" name="name" value="{{ old('name', $driver->user->name) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', $driver->user->email) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>

                        <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>

                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Driver Status
                        </label>

                        <select name="status"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">

                            <option value="offline" {{ $driver->status === 'offline' ? 'selected' : '' }}>
                                Offline
                            </option>

                            <option value="online" {{ $driver->status === 'online' ? 'selected' : '' }}>
                                Online
                            </option>

                            <option value="busy" {{ $driver->status === 'busy' ? 'selected' : '' }}>
                                Busy
                            </option>

                        </select>
                    </div>

                </div>

                <div class="bg-white rounded-2xl shadow p-6 space-y-5">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Driver Documents
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Upload file baru untuk mengganti dokumen lama.
                        </p>
                    </div>

                    @php

                        $ktp = $driver->documents->where('type', 'ktp')->first();
                        $sim = $driver->documents->where('type', 'sim')->first();
                        $selfie = $driver->documents->where('type', 'selfie')->first();

                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="space-y-3">

                            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100">

                                @if ($ktp)
                                    <img src="{{ asset('storage/' . $ktp->file_path) }}" class="w-full h-full object-cover">
                                @endif

                            </div>

                            <input type="file" name="ktp"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                        <div class="space-y-3">

                            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100">

                                @if ($sim)
                                    <img src="{{ asset('storage/' . $sim->file_path) }}"
                                        class="w-full h-full object-cover">
                                @endif

                            </div>

                            <input type="file" name="sim"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                        <div class="space-y-3">

                            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100">

                                @if ($selfie)
                                    <img src="{{ asset('storage/' . $selfie->file_path) }}"
                                        class="w-full h-full object-cover">
                                @endif

                            </div>

                            <input type="file" name="selfie"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('drivers.show', $driver->id) }}"
                    class="px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">
                    Update Driver
                </button>

            </div>

        </form>

    </div>

@endsection
