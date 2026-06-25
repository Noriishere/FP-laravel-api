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
use App\Http\Controllers\Api\ChatbotController;
Route::get('/', function () {
    return view('api-doc');
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/google/login', [AuthController::class, 'googleLogin']);
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->middleware('throttle:3,10');
Route::post('/drivers/login', [DriverAuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/payment/pakasir/webhook', [PaymentController::class, 'webhook']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,10');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/schedules/search', [ScheduleController::class, 'search']);

Route::get('/schedules', [ScheduleController::class, 'index']);

Route::get('/schedules/sorted', [ScheduleController::class, 'sorted']);
Route::get('/schedules/sortedByDay', [ScheduleController::class, 'sortedByDay']);

Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::get('/schedules/{id}/seats', [SeatController::class, 'availability']);
Route::post('/schedules/{id}/check-seat', [SeatController::class, 'checkSeat']);
Route::get('/schedules/{id}/map', [ScheduleController::class, 'map']);

Route::middleware(['auth:api', 'role:customer'])
    ->group(function () {
        Route::post('/chatbot/message', [ChatbotController::class, 'message']);
        Route::post('/payment/create', [PaymentController::class, 'create']);
        Route::post('/payment/callback', [PaymentController::class, 'callback']);
        Route::post('/payment/cancel', [PaymentController::class, 'cancel']);
        Route::get('/me/bookings', [UserController::class, 'myBookings']);
        Route::get('/me/booking/detail/{id}', [UserController::class, 'bookingHistoryDetail']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
        Route::get('/schedules/{id}/tracking', [LocationController::class, 'tracking']);
        Route::get('/schedules/{id}/route', [ScheduleController::class, 'map']);
        Route::get('/vehicles', [VehicleController::class, 'index']);
        Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
        Route::get('/payment/check/{bookingId}', [PaymentController::class, 'checkTransaction']);
    });

Route::middleware(['auth:api', 'role:driver'])->group(function () {
    Route::post('/scan-booking', [BookingController::class, 'scan']);
    Route::get('/me/schedules', [UserController::class, 'mySchedules']);
    Route::post('/driver/schedules/{id}/start', [LocationController::class, 'start']);
    Route::post('/driver/schedules/{id}/location', [LocationController::class, 'update'])->middleware('driver.location.throttle');
    Route::get('/driver/schedules/{id}/route', [DriverController::class, 'routeDetail']);
    Route::get('/driver/schedules', [DriverController::class, 'mySchedules']);
    Route::post('/driver/schedules/{id}/stop', [LocationController::class, 'stop']);
    Route::get('/driver/me', [DriverAuthController::class, 'me']);
    Route::get('/driver/history', [DriverController::class, 'history']);
});

Route::middleware(['auth:api'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'updateMe']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/bookings', [BookingController::class, 'store']);
});

Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('throttle:30,1');
