<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\AuthController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register'])
    ->name('api.auth.register');

Route::post('/verify-email', [AuthController::class, 'verifyEmail'])
    ->name('api.auth.verify-email');

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.auth.login');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('api.auth.logout');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('api.auth.forgot-password');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('api.auth.reset-password');

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });