@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Create Driver
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan driver baru beserta dokumen.
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

        <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

            @csrf

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

                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter full name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter email">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>

                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter phone number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>

                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter password">
                    </div>

                </div>

                <div class="bg-white rounded-2xl shadow p-6 space-y-5">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Driver Documents
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Upload dokumen driver.
                        </p>
                    </div>

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

            <div class="flex justify-end gap-3">

                <a href="{{ route('drivers.index') }}"
                    class="px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">
                    Create Driver
                </button>

            </div>

        </form>

    </div>

@endsection
