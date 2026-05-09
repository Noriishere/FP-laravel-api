<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('driver.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {

            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        $user = Auth::user();

        if ($user->role !== 'driver') {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Unauthorized role'
            ]);
        }

        if (!$user->hasVerifiedEmail()) {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Silakan verifikasi email terlebih dahulu'
            ]);
        }

        return redirect()
            ->route('driver.dashboard');
    }

    public function showRegister()
    {
        return view('driver.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver'
        ]);

        Driver::create([
            'user_id' => $user->id,
            'status' => 'offline',
            'verification_status' => 'pending'
        ]);

        event(new Registered($user));

        return redirect()
            ->route('driver.login')
            ->with(
                'success',
                'Registrasi berhasil, silakan verifikasi email'
            );
    }

    public function me()
    {
        $user = Auth::user();

        return view('driver.profile.index', compact('user'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect()
            ->route('driver.login');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {

            abort(404);
        }

        if (
            !hash_equals(
                (string) $hash,
                sha1($user->getEmailForVerification())
            )
        ) {

            abort(403);
        }

        if (!$user->hasVerifiedEmail()) {

            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return redirect()
            ->route('driver.login')
            ->with(
                'success',
                'Email berhasil diverifikasi'
            );
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {

            return back()->withErrors([
                'email' => 'Email sudah diverifikasi'
            ]);
        }

        $user->sendEmailVerificationNotification();

        return back()->with(
            'success',
            'Email verifikasi berhasil dikirim'
        );
    }
}