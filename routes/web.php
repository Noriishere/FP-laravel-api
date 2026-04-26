<?php

use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/', function () {
    return view('pages.home');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/admin/password', [PasswordController::class, 'update'])->name('password.update');

    Route::resource('admin/users', UsersController::class);
});

require __DIR__ . '/auth.php';
