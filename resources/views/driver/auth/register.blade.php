@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-lg">

        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Driver Register
            </h1>

            <p class="text-gray-500 text-sm mt-1">
                Buat akun driver baru
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('driver.register') }}"
            class="space-y-4"
        >
            @csrf

            <div>
                <label class="block text-sm mb-2">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>

            <div>
                <label class="block text-sm mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>

            <div>
                <label class="block text-sm mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>

            <div>
                <label class="block text-sm mb-2">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-white py-3 rounded-lg font-medium hover:opacity-90 transition"
            >
                Register
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a
                href="{{ route('driver.login') }}"
                class="text-primary font-medium"
            >
                Login
            </a>
        </div>
    </div>
</div>
@endsection