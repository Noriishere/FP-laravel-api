@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">
            Tambah User
        </h2>

        <a href="{{ route('users.index') }}" 
           class="text-sm text-gray-500 hover:text-gray-700">
            ← Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow p-6">

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-600 p-3 rounded-lg text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- NAME --}}
            <div>
                <label class="text-sm text-gray-600">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nama">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                    placeholder="Masukkan email">
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                    placeholder="Minimal 6 karakter">
            </div>

            {{-- ROLE --}}
            <div>
                <label class="text-sm text-gray-600">Role</label>
                <select name="role"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                    <option value="">Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="driver">Driver</option>
                    <option value="user">User</option>
                </select>
            </div>

            {{-- SUBMIT --}}
            <div class="pt-4">
                <button 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection