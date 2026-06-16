<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $title = 'Driver List || Admin Gassin!';
        $navtitle = 'Drivers';

        $drivers = Driver::with([
            'user',
            'documents',
        ])
            ->latest()
            ->paginate(10);

        if (request()->ajax()) {

            return response()->json([
                'data' => $drivers->items(),
                'links' => $drivers->toArray()['links'],
            ]);
        }

        return view(
            'pages.drivers.index',
            compact(
                'title',
                'navtitle',
                'drivers'
            )
        );
    }

    public function create()
    {
        $title = 'Create Driver || Admin Gassin!';
        $navtitle = 'Create Driver';

        return view(
            'pages.drivers.create',
            compact(
                'title',
                'navtitle'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'phone' => 'required|string|max:20',

            'password' => 'required|min:6',

            'ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'sim' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),

            'role' => 'driver',
        ]);

        try {
            // Coba kirim email verifikasi terlebih dahulu
            event(new Registered($user));

        } catch (\Exception $e) {
            // Jika gagal kirim email (misal SMTP error), langsung auto-verify akunnya
            $user->markEmailAsVerified();
            Log::warning('Email verifikasi gagal dikirim ke driver, otomatis diverifikasi: '.$user->email.' - Error: '.$e->getMessage());
        }

        $driver = Driver::create([

            'user_id' => $user->id,
            'phone' => $request->phone,
            'verification_status' => 'approved',

            'status' => 'offline',
        ]);

        $documents = [

            'ktp' => $request->file('ktp'),

            'sim' => $request->file('sim'),

            'selfie' => $request->file('selfie'),
        ];

        foreach ($documents as $type => $file) {

            $filePath = $file->store(
                'driver_documents',
                'public'
            );

            DriverDocument::create([

                'driver_id' => $driver->id,

                'type' => $type,

                'file_path' => $filePath,

                'status' => 'approved',

                'note' => null,
            ]);
        }

        return redirect()
            ->route('drivers.index')
            ->with(
                'success',
                'Driver berhasil dibuat'
            );
    }

    public function show($id)
    {
        $title = 'Driver Detail || Admin Gassin!';
        $navtitle = 'Driver Detail';

        $driver = Driver::with([
            'user',
            'documents',
        ])->findOrFail($id);

        return view(
            'pages.drivers.show',
            compact(
                'title',
                'navtitle',
                'driver'
            )
        );
    }

    public function edit($id)
    {
        $title = 'Edit Driver || Admin Gassin!';
        $navtitle = 'Edit Driver';

        $driver = Driver::with([
            'user',
            'documents',
        ])->findOrFail($id);

        return view(
            'pages.drivers.edit',
            compact(
                'title',
                'navtitle',
                'driver'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::with('user')
            ->findOrFail($id);

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,'.$driver->user->id,

            'phone' => 'required|string|max:20',

            'password' => 'nullable|min:6',

            'status' => 'required|in:online,offline,busy',

            'ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'sim' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $driver->user->update([

            'name' => $request->name,

            'email' => $request->email,
        ]);

        if ($request->filled('password')) {

            $driver->user->update([

                'password' => Hash::make(
                    $request->password
                ),
            ]);
        }

        $driver->update([

            'phone' => $request->phone,

            'status' => $request->status,
        ]);

        $documents = [

            'ktp' => $request->file('ktp'),

            'sim' => $request->file('sim'),

            'selfie' => $request->file('selfie'),
        ];

        foreach ($documents as $type => $file) {

            if (! $file) {
                continue;
            }

            $existing = DriverDocument::where(
                'driver_id',
                $driver->id
            )
                ->where(
                    'type',
                    $type
                )
                ->first();

            if ($existing) {

                if (
                    Storage::disk('public')->exists(
                        $existing->file_path
                    )
                ) {

                    Storage::disk('public')->delete(
                        $existing->file_path
                    );
                }

                $filePath = $file->store(
                    'driver_documents',
                    'public'
                );

                $existing->update([

                    'file_path' => $filePath,

                    'status' => 'approved',

                    'note' => null,
                ]);

            } else {

                $filePath = $file->store(
                    'driver_documents',
                    'public'
                );

                DriverDocument::create([

                    'driver_id' => $driver->id,

                    'type' => $type,

                    'file_path' => $filePath,

                    'status' => 'approved',

                    'note' => null,
                ]);
            }
        }

        return redirect()
            ->route('drivers.index')
            ->with(
                'success',
                'Driver berhasil diupdate'
            );
    }

    public function destroy($id)
    {
        $driver = Driver::with([
            'user',
            'documents',
        ])->findOrFail($id);

        foreach ($driver->documents as $document) {

            if (
                Storage::disk('public')->exists(
                    $document->file_path
                )
            ) {

                Storage::disk('public')->delete(
                    $document->file_path
                );
            }

            $document->delete();
        }

        $driver->user->delete();

        $driver->delete();

        return redirect()
            ->route('drivers.index')
            ->with(
                'success',
                'Driver berhasil dihapus'
            );
    }
}
