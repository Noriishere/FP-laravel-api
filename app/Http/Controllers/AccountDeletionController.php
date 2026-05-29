<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\AccountDeletionRequest;

class AccountDeletionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $alreadyRequested = AccountDeletionRequest::where(
            'email',
            $request->email
        )->exists();

        if ($alreadyRequested) {
            return back()->with(
                'success',
                'Permintaan penghapusan akun sudah pernah diajukan dan sedang diproses.'
            );
        }

        AccountDeletionRequest::create([
            'email' => $request->email,
            'requested_at' => now(),
        ]);

        // Ambil semua email admin
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        if (!empty($adminEmails)) {
            Mail::raw(
                "Ada permintaan penghapusan akun baru.\n\nEmail User: {$request->email}\nTanggal Request: " . now()->format('d M Y H:i'),
                function ($message) use ($adminEmails) {
                    $message->to($adminEmails)
                        ->subject('Account Deletion Request');
                }
            );
        }

        return back()->with(
            'success',
            'Permintaan penghapusan akun berhasil dikirim. Kami akan memprosesnya maksimal 7 hari kerja.'
        );
    }
}