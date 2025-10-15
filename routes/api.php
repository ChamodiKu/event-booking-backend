<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::middleware('role:organizer')->group(function () {
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{id}', [EventController::class, 'update']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
        Route::post('/events/{event_id}/tickets', [TicketController::class, 'store']);
        Route::put('/tickets/{id}', [TicketController::class, 'update']);
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);
    });
    Route::middleware('role:customer')->group(function () {
        Route::post('/tickets/{id}/bookings', [BookingController::class, 'store'])
             ->middleware('prevent.double.booking');
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
        Route::post('/bookings/{id}/payment', [PaymentController::class, 'pay']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
    });
});
