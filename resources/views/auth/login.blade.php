@extends('layouts.guest')

@section('content')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#F9F7F4] px-6 py-12">

        {{-- Background blur --}}
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute left-1/2 top-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500/10 blur-3xl">
            </div>
            <div class="absolute -top-20 -right-20 h-[300px] w-[300px] rounded-full bg-orange-400/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-[300px] w-[300px] rounded-full bg-primary/10 blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-md">

            {{-- Logo --}}
            <div class="mb-8 text-center">

                <a href="/" class="font-fraunces text-5xl font-black text-primary">
                    GASSIN
                </a>

                <p class="mt-4 text-sm leading-7 text-gray-500">
                    Login untuk melanjutkan pemesanan shuttle,
                    tracking perjalanan, dan akses tiketmu.
                </p>

            </div>

            {{-- Card --}}
            <div class="rounded-[32px] border border-[#E8E3DC] bg-white p-8 shadow-xl shadow-black/5">

                <div class="mb-8">

                    <h1 class="font-fraunces text-4xl font-black text-[#111010]">
                        Welcome Back
                    </h1>

                    <p class="mt-3 text-sm leading-7 text-gray-500">
                        Masuk ke akun GASSIN milikmu.
                    </p>

                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Error --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">

                    @csrf

                    {{-- Email --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Email Address
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="you@example.com"
                            class="w-full rounded-2xl border border-[#E8E3DC] bg-[#F9F7F4] px-4 py-3 text-sm text-dark outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10">

                    </div>

                    {{-- Password --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Password
                        </label>

                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full rounded-2xl border border-[#E8E3DC] bg-[#F9F7F4] px-4 py-3 text-sm text-dark outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10">

                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between pt-1">

                        <label class="flex items-center gap-2 text-sm text-gray-600">

                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-primary focus:ring-primary">

                            Remember me

                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-primary transition hover:opacity-80">
                                Forgot password?
                            </a>
                        @endif

                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-primary px-6 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-primaryDark">

                        <i class="fa-solid fa-right-to-bracket"></i>

                        Log In

                    </button>

                </form>

                {{-- Bottom --}}
                <div class="mt-8 border-t border-[#E8E3DC] pt-6 text-center">

                    <p class="text-sm text-gray-500">
                        Belum punya akun?

                        <a href="{{ route('register') }}" class="font-bold text-primary hover:underline">
                            Daftar sekarang
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </div>
@endsection
