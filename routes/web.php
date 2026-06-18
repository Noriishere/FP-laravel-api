<?php

use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\AdminLandingController;
use App\Http\Controllers\Dashboard\BookingController;
use App\Http\Controllers\Dashboard\RouteController;
use App\Http\Controllers\Dashboard\ScheduleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Dashboard\ApiLogController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\TripMonitoringController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::get('/admin', [AdminLandingController::class, 'index'])->name('admin.landing');

Route::get('/reset-password/{token}', function (
    Request $request,
    string $token
) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
    ]);})->name('password.reset');

Route::get('/', [LandingPageController::class, 'index'])->name('landing-pages');

Route::get('/privacy-policy', function () {return view('pages.privacy-policy');})->name('privacy-policy');

Route::get('/term-of-services', function () {return view('pages.terms-of-service');})->name('terms-of-service');

Route::post('/account-deletion',[AccountDeletionController::class, 'store'])->name('account.deletion.request');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function () {
    Route::resource('/bookings', BookingController::class);
    Route::resource('/routes', RouteController::class);
    Route::resource('/schedules', ScheduleController::class);

    Route::resource('/vehicles', VehicleController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/users/deleted', [UsersController::class, 'deletedAccounts'])->name('users.deleted');
    Route::patch('/users/{id}/restore', [UsersController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UsersController::class, 'forceDelete'])->name('users.forceDelete');
    Route::resource('/users', UsersController::class);
    Route::resource('/drivers', DriverController::class);
    Route::get('/trip-monitoring', [TripMonitoringController::class, 'index'])->name('trip-monitoring.index');
    Route::get('/trip-monitoring/{id}', [TripMonitoringController::class, 'show'])->name('trip-monitoring.show');
    Route::get('/trip-monitoring/{id}/data', [TripMonitoringController::class, 'trackingData'])->name('trip-monitoring.data');
    Route::get('/activity', [ApiLogController::class, 'activity'])->name('api-logs.activity');
    Route::get('/crashes', [ApiLogController::class, 'crashes'])->name('api-logs.crashes');
    Route::get('/crashes/{log}', [ApiLogController::class, 'showCrash'])->name('api-logs.crashes.show');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/available-drivers', [ScheduleController::class, 'availableDrivers']);
    Route::get('/available-vehicles', [ScheduleController::class, 'availableVehicles']);
});

require __DIR__.'/auth.php';
