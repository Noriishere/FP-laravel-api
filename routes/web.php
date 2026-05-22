<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\DriverDocumentController;
use App\Http\Controllers\Dashboard\TripMonitoringController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Driver\AuthController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
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

Route::prefix('driver')
    ->name('driver.')
    ->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('/', function () {
                return view('driver.home');
            });

            Route::get('/login', [
                AuthController::class,
                'showLogin',
            ])->name('login');

            Route::post('/login', [
                AuthController::class,
                'login',
            ]);

            Route::get('/register', [
                AuthController::class,
                'showRegister',
            ])->name('register');

            Route::post('/register', [
                AuthController::class,
                'register',
            ]);
        });

        Route::get(
            '/email/verify/{id}/{hash}',
            [AuthController::class, 'verify']
        )->name('verification.verify');

        Route::middleware('auth')->group(function () {

            Route::get('/dashboard', [
                DriverDashboardController::class,
                'dashboard',
            ])->name('dashboard');

            Route::get('/me', [
                AuthController::class,
                'me',
            ])->name('me');

            Route::post('/logout', [
                AuthController::class,
                'logout',
            ])->name('logout');

            Route::post('/email/resend', [
                AuthController::class,
                'resend',
            ])->name('verification.resend');
            Route::get('/documents', [
                DriverDashboardController::class,
                'documents',
            ])->name('documents');

            Route::post('/documents/upload', [
                DriverDashboardController::class,
                'uploadDocument',
            ])->name('documents.upload');
        });
    });

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
    Route::get('/admin/driver-documents', [DriverDocumentController::class, 'index'])->name('driver-documents.index');
    Route::get('/admin/driver-documents/{id}', [DriverDocumentController::class, 'show'])->name('driver-documents.show');
    Route::post('/admin/driver-documents/{id}/approve', [DriverDocumentController::class, 'approve'])->name('driver-documents.approve');
    Route::post('/admin/driver-documents/{id}/reject', [DriverDocumentController::class, 'reject'])->name('driver-documents.reject');
    Route::get('/admin/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::get('/users/deleted',[UsersController::class, 'deletedAccounts'])->name('users.deleted');
    Route::patch('/admin/users/{id}/restore',[UsersController::class, 'restore'])->name('users.restore');
    Route::delete('/admin/users/{id}/force-delete',[UsersController::class, 'forceDelete'])->name('users.forceDelete');
    Route::get('/admin/trip-monitoring',[TripMonitoringController::class, 'index'])->name('trip-monitoring.index');
    Route::get('/admin/trip-monitoring/data',[TripMonitoringController::class, 'data'])->name('trip-monitoring.data');
});
Route::middleware(['auth', 'role:driver'])->group(function () {});

require __DIR__.'/auth.php';
