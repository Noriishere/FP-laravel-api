@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow">
        <h1 class="text-2xl font-bold mb-6">
            Driver Login
        </h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('driver.login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >
            </div>

            <div class="mb-6">
                <label class="block text-sm mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-white py-3 rounded-lg"
            >
                Login
            </button>
        </form>
    </div>
</div>
@endsection