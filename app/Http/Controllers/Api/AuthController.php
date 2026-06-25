<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required',
        ]);

        $client = new Client([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($request->id_token);

        if (! $payload) {
            return response()->json([
                'message' => 'Invalid Google Token',
            ], 401);
        }

        $user = User::where('email', $payload['email'])->first();

        if ($user && $user->role !== 'customer') {
            return response()->json([
                'message' => 'Login ini khusus customer',
            ], 403);
        }

        try {
            $user = User::firstOrCreate(
                ['email' => $payload['email']],
                [
                    'name' => $payload['name'],
                    'google_id' => $payload['sub'],
                    'provider' => 'google',
                    'role' => 'customer',
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(32)),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            $user = User::where('email', $payload['email'])->firstOrFail();
        }

        if (! $user->google_id) {
            $user->update([
                'google_id' => $payload['sub'],
                'provider' => 'google',
            ]);
        }

        // update google_id jika belum ada
        if (! $user->google_id) {
            $user->update([
                'google_id' => $payload['sub'],
                'provider' => 'google',
            ]);
        }

        $token = auth('api')->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registered successfully. Please verify your email.',
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['Email atau password salah'],
                ],
            ], 401);
        }

        $user = auth('api')->user();
        if ($user->role !== 'customer') {

            auth('api')->logout();

            return response()->json([

                'message' => 'Unauthorized role',
                'errors' => [
                    'role' => ['Login ini khusus customer'],
                ],
            ], 403);
        }
        if (! $user->hasVerifiedEmail()) {
            auth('api')->logout();

            return response()->json([
                'message' => 'Email not verified',
                'errors' => [
                    'email' => ['Silakan verifikasi email terlebih dahulu'],
                ],
            ], 403);
        }
        $user->update([
            'last_user_agent' => $request->userAgent(),
            'last_ip_address' => $request->ip(),
            'last_login_at' => now(),
        ]);

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ]);
    }

    public function me()
    {
        $user = auth('api')->user();

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'role' => $user->role,

                'email_verified_at' => $user->email_verified_at,

                'created_at' => $user->created_at,
            ],
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out']);
    }

    public function updateMe(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name ?? $user->name;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function refresh()
    {
        \Log::info('REFRESH HIT');
        \Log::info('REFRESH HEADER', [
            'auth' => request()->header('Authorization'),
        ]);
        try {
            $token = auth('api')->refresh();

            \Log::info('REFRESH SUCCESS');

            return $this->respondWithToken($token);

        } catch (TokenBlacklistedException $e) {

            \Log::info('REFRESH BLACKLISTED');

            return response()->json([
                'message' => 'Token has been blacklisted',
            ], 401);
        }
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (! $user) {

            return view('verify-status', [
                'status' => 'not-found',
            ]);
        }

        if (! hash_equals(
            (string) $hash,
            sha1($user->getEmailForVerification())
        )) {

            return view('verify-status', [
                'status' => 'invalid',
            ]);
        }

        if ($user->hasVerifiedEmail()) {

            return view('verify-status', [
                'status' => 'already',
            ]);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return view('verify-status', [
            'status' => 'success',
        ]);
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email resent',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => __($status),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => __($status),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 400);
    }
}
