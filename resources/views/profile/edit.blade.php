@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto py-8 space-y-6">

    {{-- HEADER --}}
    <h2 class="text-lg font-semibold text-gray-700">
        Profile Settings
    </h2>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-600 p-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- UPDATE PROFILE --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Informasi Profile
        </h3>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            {{-- NAME --}}
            <div>
                <label class="text-sm text-gray-600">Nama</label>
                <input type="text" name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Update Profile
            </button>
        </form>
    </div>

    {{-- UPDATE PASSWORD --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Ubah Password
        </h3>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm text-gray-600">Password Lama</label>
                <input type="password" name="current_password"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600">Password Baru</label>
                <input type="password" name="password"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">
                Update Password
            </button>
        </form>
    </div>

    {{-- DELETE ACCOUNT --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-sm font-semibold text-red-600 mb-4">
            Hapus Akun
        </h3>

        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label class="text-sm text-gray-600">Konfirmasi Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <button onclick="return confirm('Yakin mau hapus akun?')" 
                class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Hapus Akun
            </button>
        </form>
    </div>

</div>

@endsection