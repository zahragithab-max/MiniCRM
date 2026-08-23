<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Customer\CustomerController;
use App\Modules\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register'])
    ->name('auth.register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('auth.login');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('auth.logout');

Route::middleware('auth')->group(function () {

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::get('/customers/{id}', [CustomerController::class, 'show'])
        ->name('customers.show');

    Route::put('/customers/{id}', [CustomerController::class, 'update'])
        ->name('customers.update');

    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy');
});