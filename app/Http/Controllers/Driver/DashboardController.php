<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $isVerified = false;

        if ($driver) {

            $approvedCount = $driver->documents
                ->where('status', 'approved')
                ->count();

            $isVerified = $approvedCount >= 3;
        }

        return view(
            'driver.dashboard',
            compact(
                'title',
                'navtitle',
                'driver',
                'isVerified'
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

            $status = trim(
                strtolower($existing->status)
            );

            if ($status === 'pending') {

                return back()->withErrors([
                    'file' => 'Dokumen sedang menunggu verifikasi'
                ]);
            }

            if ($status === 'approved') {

                return back()->withErrors([
                    'file' => 'Dokumen sudah disetujui'
                ]);
            }

            if ($status === 'rejected') {

                if (
                    Storage::disk('public')->exists(
                        $existing->file_path
                    )
                ) {

                    Storage::disk('public')->delete(
                        $existing->file_path
                    );
                }

                $filePath = $request->file('file')
                    ->store(
                        'driver_documents',
                        'public'
                    );

                $existing->update([
                    'file_path' => $filePath,
                    'status' => 'pending',
                    'note' => null,
                ]);

                return redirect()
                    ->route('driver.documents')
                    ->with(
                        'success',
                        'Dokumen berhasil dikirim ulang'
                    );
            }
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
            'status' => 'pending',
            'note' => null,
        ]);

        return redirect()
            ->route('driver.documents')
            ->with(
                'success',
                'Dokumen berhasil diupload'
            );
    }
}
