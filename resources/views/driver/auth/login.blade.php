@extends('layouts.guest')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,700;9..144,900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-display { font-family: 'Fraunces', serif; font-optical-sizing: auto; }
    .input-field:focus { outline: none; border-color: #E82C2C; box-shadow: 0 0 0 4px rgba(232,44,44,0.08); }
</style>

<div class="min-h-screen grid md:grid-cols-2">

    {{-- LEFT PANEL --}}
    <div class="hidden md:flex flex-col justify-between bg-[#111010] px-14 py-12 relative overflow-hidden">

        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -bottom-20 -right-20 w-96 h-96 rounded-full"
             style="background: radial-gradient(circle, rgba(232,44,44,0.22) 0%, transparent 65%);"></div>

        <span class="font-display text-2xl font-black text-[#E82C2C] relative z-10">GASSIN</span>

        <div class="relative z-10">
            <p class="text-[#E82C2C] text-xs font-bold tracking-widest uppercase mb-5 flex items-center gap-2">
                <span class="block w-5 h-0.5 bg-[#E82C2C] rounded"></span>
                Portal Driver
            </p>

            <h2 class="font-display font-black text-white text-4xl leading-tight mb-5">
                Selamat datang<br><span class="text-[#E82C2C]">kembali, Driver</span>
            </h2>

            <p class="text-white/40 text-sm leading-relaxed max-w-xs mb-10">
                Login untuk melanjutkan proses pendaftaran atau mengakses dashboard driver kamu.
            </p>

            {{-- Flow steps --}}
            <div class="flex flex-col gap-0">

                {{-- Step 1 — done --}}
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-white/30 text-xs"></i>
                        </div>
                        <div class="w-px flex-1 bg-white/10 my-1" style="min-height:28px;"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-white/30 text-sm font-semibold line-through">Buat akun</p>
                    </div>
                </div>

                {{-- Step 2 — active --}}
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-[#E82C2C] flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">2</div>
                        <div class="w-px flex-1 bg-white/10 my-1" style="min-height:28px;"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-white text-sm font-semibold">Login ke akun kamu</p>
                        <p class="text-white/40 text-xs mt-0.5 leading-relaxed">Masuk menggunakan email dan password yang didaftarkan.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0 text-white/40 text-xs font-bold">3</div>
                        <div class="w-px flex-1 bg-white/10 my-1" style="min-height:28px;"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-white/50 text-sm font-semibold">Lengkapi data diri</p>
                        <p class="text-white/25 text-xs mt-0.5 leading-relaxed">Isi profil dan dokumen driver di website.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0 text-white/40 text-xs font-bold">4</div>
                    </div>
                    <div>
                        <p class="text-white/50 text-sm font-semibold">Download & mulai terima order</p>
                        <p class="text-white/25 text-xs mt-0.5 leading-relaxed">Unduh aplikasi GASSIN dan langsung aktif.</p>
                    </div>
                </div>

            </div>
        </div>

        <span class="text-white/20 text-xs relative z-10">© 2026 GASSIN Shuttle System</span>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="flex items-center justify-center bg-[#F9F7F4] px-6 py-12">
        <div class="w-full max-w-sm">

            <div class="mb-8">
                <p class="text-[#E82C2C] text-xs font-bold tracking-widest uppercase mb-3">Langkah 2 dari 4</p>
                <h1 class="font-display font-black text-[#111010] text-3xl leading-snug mb-2">
                    Masuk ke akun<br>driver kamu
                </h1>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Setelah login, lengkapi data dirimu di website untuk mengaktifkan akun driver.
                </p>
            </div>

            @if(session('success'))
                <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                    <i class="fa-solid fa-circle-check mt-0.5 flex-shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('driver.login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-[#111010] tracking-wide mb-2">Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm pointer-events-none"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="email@kamu.com" autocomplete="email" required
                            class="input-field w-full pl-11 pr-4 py-3 bg-white border border-[#E8E3DC] rounded-xl text-sm text-[#111010] placeholder-gray-300 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#111010] tracking-wide mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm pointer-events-none"></i>
                        <input id="password" type="password" name="password"
                            placeholder="••••••••" autocomplete="current-password" required
                            class="input-field w-full pl-11 pr-11 py-3 bg-white border border-[#E8E3DC] rounded-xl text-sm text-[#111010] placeholder-gray-300 transition">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 transition">
                            <i class="fa-solid fa-eye text-sm" id="pw-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 bg-[#E82C2C] hover:bg-[#C41F1F] text-white font-bold text-sm py-3.5 rounded-xl transition hover:-translate-y-0.5 shadow-[0_6px_20px_rgba(232,44,44,0.28)] hover:shadow-[0_10px_28px_rgba(232,44,44,0.36)] active:translate-y-0">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Masuk Sekarang
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-gray-400">
                Belum punya akun?
                <a href="{{ route('driver.register') }}" class="text-[#E82C2C] font-semibold hover:underline ml-1">Daftar di sini</a>
            </p>

            <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 mt-3 text-xs text-gray-400 hover:text-gray-600 font-medium transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke halaman utama
            </a>

        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('pw-icon');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'fa-solid fa-eye text-sm' : 'fa-solid fa-eye-slash text-sm';
    }
</script>

@endsection