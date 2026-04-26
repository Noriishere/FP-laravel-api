<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diupdate');
    }
}