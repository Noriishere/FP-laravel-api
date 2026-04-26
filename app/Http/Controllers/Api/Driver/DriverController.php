<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'driver') {
            return response()->json([
                'message' => 'User is not a driver'
            ], 403);
        }

        $existing = Driver::where('user_id', $user->id)->first();

        if ($existing) {
            return response()->json([
                'message' => 'Driver already exists'
            ], 400);
        }

        $driver = Driver::create([
            'user_id' => $user->id,
            'status' => 'offline',
            'verification_status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Driver profile created',
            'driver' => $driver
        ]);
    }

    public function uploadDocument(Request $request, $id)
    {
        $user = Auth::user();

        $driver = Driver::find($id);

        if (!$driver || $driver->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized driver access'
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:ktp,sim,selfie',
            'file' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $existing = DriverDocument::where('driver_id', $id)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Document already uploaded'
            ], 400);
        }

        $filePath = $request->file('file')->store('driver_documents', 'public');

        $doc = DriverDocument::create([
            'driver_id' => $driver->id,
            'type' => $request->type,
            'file_path' => $filePath,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Document uploaded',
            'document' => $doc
        ]);
    }
}