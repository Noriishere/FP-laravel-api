<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class AuthController extends Controller
{
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

        if ($user->role !== 'driver') {
            auth('api')->logout();

            return response()->json([
                'message' => 'Unauthorized role',
                'errors' => [
                    'role' => ['User bukan driver'],
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

        $driver = $user->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        $documents = $driver->documents
            ->keyBy('type');

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'role' => $user->role,

                'email_verified_at' => $user->email_verified_at,

                'created_at' => $user->created_at,

                'driver' => [

                    'id' => $driver->id,

                    'status' => $driver->status,

                    'verification_status' => $driver->verification_status,

                    'documents' => [

                        'ktp' => isset($documents['ktp'])
                            ? asset(
                                'storage/' .
                                $documents['ktp']->file_path
                            )
                            : null,

                        'sim' => isset($documents['sim'])
                            ? asset(
                                'storage/' .
                                $documents['sim']->file_path
                            )
                            : null,

                        'selfie' => isset($documents['selfie'])
                            ? asset(
                                'storage/' .
                                $documents['selfie']->file_path
                            )
                            : null,
                    ],
                ],
            ],
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out']);
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

    public function resend(Request $request)
    {
        $user = auth('api')->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email sent',
        ]);
    }
}
