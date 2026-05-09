<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\Driver\AuthController as DriverAuthController;
use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/email/resend', [AuthController::class, 'resendVerification']);
Route::post('/drivers/register', [DriverAuthController::class, 'register']);
Route::post('/drivers/login', [DriverAuthController::class, 'login']);
Route::post('/payment/pakasir/webhook', [PaymentController::class, 'webhook']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/debug', function () {
    return response()->json(['masuk' => true]);
});

Route::get('/schedules', [ScheduleController::class, 'index']);

Route::get('/schedules/sorted', [ScheduleController::class, 'sorted']);
Route::get('/schedules/sortedByDay', [ScheduleController::class, 'sortedByDay']);

Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::get('/schedules/{id}/seats', [SeatController::class, 'availability']);
Route::get('/schedules/{id}/map', [ScheduleController::class, 'map']);

Route::middleware(['auth:api', 'role:customer'])
    ->group(function () {
        Route::post('/payment/create', [PaymentController::class, 'create']);
        Route::post('/payment/callback', [PaymentController::class, 'callback']);
        Route::post('/payment/cancel', [PaymentController::class, 'cancel']);
        Route::get('/me/bookings', [UserController::class, 'myBookings']);
        Route::get('/schedules/{id}/tracking', [LocationController::class, 'tracking']);
        Route::get('/schedules/{id}/route', [ScheduleController::class, 'map']);
        Route::get('/vehicles', [VehicleController::class, 'index']);
        Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
    });

Route::middleware(['auth:api', 'role:driver'])->group(function () {
    Route::post('/scan-booking', [BookingController::class, 'scan']);
    Route::post('/drivers/create', [DriverController::class, 'create']);
    Route::post('/drivers/{id}/documents', [DriverController::class, 'uploadDocument']);
    Route::get('/me/schedules', [UserController::class, 'schedules']);
    Route::post('/location', [LocationController::class, 'update']);
});

Route::middleware(['auth:api'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'updateMe']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::post('/bookings', [BookingController::class, 'store']);
});
