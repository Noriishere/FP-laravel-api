@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow">

        <div class="mb-6 flex items-start justify-between">

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
            <div class="mb-5 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                <ul class="list-disc pl-5 text-sm space-y-1">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        @endif

        <form action="{{ route('drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">

            @csrf
            @method('PUT')

            <div>

                <h2 class="text-lg font-semibold text-gray-800 mb-5">
                    Driver Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name
                        </label>

                        <input type="text" name="name" value="{{ old('name', $driver->user->name) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', $driver->user->email) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>

                        <input type="text" name="phone" value="{{ old('phone', $driver->user->phone) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>

                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            License Number
                        </label>

                        <input type="text" name="license_number"
                            value="{{ old('license_number', $driver->license_number) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Driver Status
                        </label>

                        <select name="status"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

                            <option value="offline" {{ $driver->status === 'offline' ? 'selected' : '' }}>
                                Offline
                            </option>

                            <option value="online" {{ $driver->status === 'online' ? 'selected' : '' }}>
                                Online
                            </option>

                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>

                        <textarea name="address" rows="4"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address', $driver->address) }}</textarea>
                    </div>

                </div>

            </div>

            <div class="border-t pt-8">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Driver Documents
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Upload file baru untuk mengganti dokumen lama.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    @php

                        $ktp = $driver->documents->where('type', 'ktp')->first();
                        $sim = $driver->documents->where('type', 'sim')->first();
                        $selfie = $driver->documents->where('type', 'selfie')->first();

                    @endphp

                    <div class="border border-gray-200 rounded-2xl overflow-hidden">

                        <div class="aspect-square bg-gray-100">

                            @if ($ktp)
                                <img src="{{ asset('storage/' . $ktp->file_path) }}" class="w-full h-full object-cover">
                            @endif

                        </div>

                        <div class="p-4 space-y-3">

                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    KTP
                                </h3>

                                <p class="text-sm text-green-600">
                                    Approved
                                </p>
                            </div>

                            <input type="file" name="ktp" accept=".jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                    </div>

                    <div class="border border-gray-200 rounded-2xl overflow-hidden">

                        <div class="aspect-square bg-gray-100">

                            @if ($sim)
                                <img src="{{ asset('storage/' . $sim->file_path) }}" class="w-full h-full object-cover">
                            @endif

                        </div>

                        <div class="p-4 space-y-3">

                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    SIM
                                </h3>

                                <p class="text-sm text-green-600">
                                    Approved
                                </p>
                            </div>

                            <input type="file" name="sim" accept=".jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                    </div>

                    <div class="border border-gray-200 rounded-2xl overflow-hidden">

                        <div class="aspect-square bg-gray-100">

                            @if ($selfie)
                                <img src="{{ asset('storage/' . $selfie->file_path) }}" class="w-full h-full object-cover">
                            @endif

                        </div>

                        <div class="p-4 space-y-3">

                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    Selfie
                                </h3>

                                <p class="text-sm text-green-600">
                                    Approved
                                </p>
                            </div>

                            <input type="file" name="selfie" accept=".jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">

                        </div>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 border-t">

                <a href="{{ route('drivers.show', $driver->id) }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">
                    Update Driver
                </button>

            </div>

        </form>

    </div>

@endsection