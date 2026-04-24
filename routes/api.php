<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\Driver\AuthController as DriverAuthController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SeatController;
use Illuminate\Support\Facades\Route;

// AUTH USER
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AUTH DRIVER
Route::post('/register/driver', [DriverAuthController::class, 'register']);
Route::post('/login/driver', [DriverAuthController::class, 'login']);

// EMAIL VERIFICATION
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

// PUBLIC DATA
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::get('/schedules/{id}/seats', [SeatController::class, 'availability']);


// 🔐 JWT PROTECTED
Route::middleware(['auth:api'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // BOOKINGS
    Route::post('/bookings', [BookingController::class, 'store']);
});