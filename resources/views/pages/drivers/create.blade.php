@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow">

        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-800">
                Create Driver
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Tambahkan driver baru beserta dokumen pendukung.
            </p>
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

        <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>

                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter driver name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter email">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>

                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter phone number">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>

                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter password">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        License Number
                    </label>

                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter license number">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>

                    <textarea name="address" rows="3"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Enter address">{{ old('address') }}</textarea>
                </div>

            </div>

            <div class="border-t pt-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-5">
                    Driver Documents
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload KTP
                        </label>

                        <input type="file" name="ktp" accept=".jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload SIM
                        </label>

                        <input type="file" name="sim" accept=".jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Selfie
                        </label>

                        <input type="file" name="selfie" accept=".jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3">
                    </div>

                </div>

            </div>

            <div class="flex items-center justify-end gap-3 pt-4">

                <a href="{{ route('drivers.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                    Create Driver
                </button>

            </div>

        </form>

    </div>

@endsection
