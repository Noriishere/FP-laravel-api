@extends('layouts.guest')

@section('content')
    <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl">
        <div class="flex flex-col overflow-y-auto md:flex-row">

            <!-- KIRI (kosong / bisa nanti isi apa aja) -->
            <div class="hidden md:block md:w-1/2 bg-gray-100"></div>

            <!-- KANAN (FORM) -->
            <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">

                <div class="w-full">

                    <h1 class="mb-4 text-xl font-semibold text-gray-700">
                        Login
                    </h1>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- EMAIL -->
                        <label class="block text-sm">
                            <span class="text-gray-700">Email</span>
                            <x-text-input class="block w-full mt-1" type="email" name="email" :value="old('email')" required
                                autofocus />
                        </label>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        <!-- PASSWORD -->
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700">Password</span>
                            <x-text-input class="block w-full mt-1" type="password" name="password" required />
                        </label>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <!-- REMEMBER -->
                        <div class="flex items-center justify-between mt-4">
                            <label class="flex items-center text-sm text-gray-600">
                                <input type="checkbox" name="remember"
                                    class="rounded border-gray-300 text-purple-600 shadow-sm">
                                <span class="ml-2">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:underline">
                                    Forgot?
                                </a>
                            @endif
                        </div>

                        <!-- BUTTON -->
                        <button
                            class="block w-full px-4 py-2 mt-4 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                            Log in
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
