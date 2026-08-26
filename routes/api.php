<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Modules\Settings\Auth\Http\Controllers\AuthController;

use App\Modules\Settings\Roles\Http\Controllers\RoleController;
use App\Modules\Settings\Roles\Http\Controllers\PermissionController;
use App\Modules\Settings\Roles\Http\Controllers\UserRoleController;


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


Route::middleware('auth:sanctum')->group(function () {

  

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('api.roles.index');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create')
        ->name('api.roles.store');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->name('api.roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->name('api.roles.destroy');

    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])
        ->name('api.roles.permissions.sync');



    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('api.permissions.index');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->name('api.permissions.store');

    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])
        ->name('api.permissions.update');

    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
        ->name('api.permissions.destroy');


 
    Route::get('/users/{user}/roles', [UserRoleController::class, 'index'])
        ->name('api.users.roles.index');

    Route::put('/users/{user}/roles', [UserRoleController::class, 'sync'])
        ->name('api.users.roles.sync');

    Route::post('/users/{user}/roles/{role}', [UserRoleController::class, 'assign'])
        ->name('api.users.roles.assign');

    Route::delete('/users/{user}/roles/{role}', [UserRoleController::class, 'remove'])
        ->name('api.users.roles.remove');

});