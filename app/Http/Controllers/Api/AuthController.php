<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
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
                    'role' => ['Login ini khusus customer']
                ]
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
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Email verified']);
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
