<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Dashboard\ApiLogController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\TripMonitoringController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\DashboardController;
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


Route::get('/admin/', function () {
    return view('pages.home');
});

Route::get('/reset-password/{token}', function (
    Request $request,
    string $token
) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
    ]);
})->name('password.reset');

Route::get('/', function () {
    return view('pages.landing-pages');
})->name('landing-pages');

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-services', function () {
    return view('pages.terms-of-service');
})->name('terms-of-service');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/admin/bookings', BookingController::class);
    Route::resource('/admin/routes', RouteController::class);
    Route::resource('/admin/schedules', ScheduleController::class);
    Route::get('/available-drivers', [ScheduleController::class, 'availableDrivers']);
    Route::get('/available-vehicles', [ScheduleController::class, 'availableVehicles']);
    Route::resource('admin/vehicles', VehicleController::class);

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/admin/password', [PasswordController::class, 'update'])->name('password.update');

    Route::resource('/admin/users', UsersController::class);
    Route::resource('/admin/drivers', DriverController::class);
    Route::get('/users/deleted', [UsersController::class, 'deletedAccounts'])->name('users.deleted');
    Route::patch('/admin/users/{id}/restore', [UsersController::class, 'restore'])->name('users.restore');
    Route::delete('/admin/users/{id}/force-delete', [UsersController::class, 'forceDelete'])->name('users.forceDelete');
    Route::get('/admin/trip-monitoring', [TripMonitoringController::class, 'index'])->name('trip-monitoring.index');
    Route::get('/admin/trip-monitoring/{id}', [TripMonitoringController::class, 'show'])->name('trip-monitoring.show');
    Route::get('/admin/trip-monitoring/{id}/data', [TripMonitoringController::class, 'trackingData'])->name('trip-monitoring.data');
    Route::get('/activity', [ApiLogController::class, 'activity'])->name('api-logs.activity');
    Route::get('/crashes', [ApiLogController::class, 'crashes'])->name('api-logs.crashes');
    Route::get('/crashes/{log}', [ApiLogController::class, 'showCrash'])->name('api-logs.crashes.show');
});
Route::middleware(['auth', 'role:driver'])->group(function () {});

require __DIR__.'/auth.php';
