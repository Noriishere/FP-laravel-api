@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">
            Edit User
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
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="text-sm text-gray-600">Nama</label>
                <input type="text" name="name" 
                    value="{{ old('name', $user->name) }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email" 
                    value="{{ old('email', $user->email) }}"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </div>

            {{-- PASSWORD (OPTIONAL) --}}
            <div>
                <label class="text-sm text-gray-600">Password (opsional)</label>
                <input type="password" name="password"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                    placeholder="Kosongkan jika tidak diubah">
            </div>

            {{-- ROLE --}}
            <div>
                <label class="text-sm text-gray-600">Role</label>
                <select name="role"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">

                    <option value="admin" {{ old('role', $user->role)=='admin'?'selected':'' }}>Admin</option>
                    <option value="driver" {{ old('role', $user->role)=='driver'?'selected':'' }}>Driver</option>
                    <option value="user" {{ old('role', $user->role)=='user'?'selected':'' }}>User</option>

                </select>
            </div>

            {{-- SUBMIT --}}
            <div class="pt-4 flex justify-between">
                <button 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Update
                </button>

                <a href="{{ route('users.index') }}" 
                   class="text-sm text-gray-500 hover:text-gray-700">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

@endsection