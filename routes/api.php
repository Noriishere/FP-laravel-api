<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\Driver\AuthController as DriverAuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/register/driver', [DriverAuthController::class, 'register']);
Route::post('/login/driver', [DriverAuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::get('/schedules/{id}/seats', [SeatController::class, 'availability']);
Route::get('/schedules/{id}/map', [ScheduleController::class, 'map']);

Route::middleware(['auth:api', 'role:customer'])
    ->group(function () {
        Route::get('/me/bookings', [UserController::class, 'myBookings']);
        Route::get('/schedules/{id}/tracking', [LocationController::class, 'tracking']);
        Route::get('/schedules/{id}/route', [ScheduleController::class, 'map']);
    });

Route::middleware(['auth:api', 'role:driver'])
    ->prefix('driver')
    ->group(function () {
        Route::get('/me/schedules', [UserController::class, 'schedules']);
        Route::post('/location', [LocationController::class, 'update']);
    });

Route::middleware(['auth:api'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::post('/bookings', [BookingController::class, 'store']);
});
