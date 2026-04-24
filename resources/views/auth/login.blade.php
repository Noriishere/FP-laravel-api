@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50">

    <div class="w-full max-w-md p-8 bg-white border border-gray-200 rounded-lg shadow">

        <h1 class="mb-6 text-xl font-semibold text-gray-800">
            Login
        </h1>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Global Error -->
        @if ($errors->any())
            <div class="mb-4 p-3 text-sm text-red-600 bg-red-50 border border-red-200 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#C00707] focus:border-[#C00707]"
                >
            </div>

            <!-- PASSWORD -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#C00707] focus:border-[#C00707]"
                >
            </div>

            <!-- REMEMBER -->
            <div class="flex items-center justify-between mt-4">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-[#C00707] hover:underline">
                        Forgot password
                    </a>
                @endif
            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full mt-6 py-2 text-white bg-[#C00707] rounded-md hover:bg-red-800 transition">
                Log in
            </button>
        </form>

    </div>

</div>
@endsection