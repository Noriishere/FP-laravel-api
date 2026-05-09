<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $title = 'Driver Dashboard || Gassin!';
        $navtitle = 'Driver Dashboard';

        $driver = Driver::with([
            'documents'
        ])->where(
            'user_id',
            auth()->id()
        )->first();

        return view(
            'driver.dashboard',
            compact(
                'title',
                'navtitle',
                'driver'
            )
        );
    }

    public function documents()
    {
        $title = 'Driver Documents || Gassin!';
        $navtitle = 'Driver Documents';

        $driver = Driver::with([
            'documents'
        ])->where(
            'user_id',
            auth()->id()
        )->first();

        return view(
            'driver.documents',
            compact(
                'title',
                'navtitle',
                'driver'
            )
        );
    }

    public function uploadDocument(Request $request)
    {
        $driver = Driver::where(
            'user_id',
            auth()->id()
        )->first();

        if (!$driver) {

            return back()->withErrors([
                'driver' => 'Driver tidak ditemukan'
            ]);
        }

        $request->validate([
            'type' => 'required|in:ktp,sim,selfie',
            'file' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $existing = DriverDocument::where(
            'driver_id',
            $driver->id
        )
            ->where(
                'type',
                $request->type
            )
            ->first();

        if ($existing) {

            return back()->withErrors([
                'file' => 'Dokumen sudah diupload'
            ]);
        }

        $filePath = $request->file('file')
            ->store(
                'driver_documents',
                'public'
            );

        DriverDocument::create([
            'driver_id' => $driver->id,
            'type' => $request->type,
            'file_path' => $filePath,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('driver.documents')
            ->with(
                'success',
                'Dokumen berhasil diupload'
            );
    }
}