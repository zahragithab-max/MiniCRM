<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Modules\Settings\Auth\Http\Controllers\AuthController;
use App\Modules\Settings\Auth\Http\Controllers\UserController;
use App\Modules\Settings\Roles\Http\Controllers\RoleRecordAccessController;
use App\Modules\Leads\Http\Controllers\LeadController;
use App\Modules\Settings\Roles\Http\Controllers\RoleController;
use App\Modules\Settings\Roles\Http\Controllers\PermissionController;
use App\Modules\Settings\Roles\Http\Controllers\UserRoleController;



Route::post('/register', [AuthController::class, 'register'])
    ->name('api.auth.register');

Route::post('/verify-email', [AuthController::class, 'verifyEmail'])
    ->name('api.auth.verify-email');

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.auth.login');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('api.auth.forgot-password');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('api.auth.reset-password');




Route::middleware('auth:sanctum')->group(function () {

  

  Route::get('/user', function (Request $request) {
    return response()->json([
        'message' => 'USER ROUTE OK',
        'user' => $request->user(),
    ]);
 })->name('api.user');


 

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('api.roles.index');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create')
        ->name('api.roles.store');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.edit')
        ->name('api.roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete')
        ->name('api.roles.destroy');

    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])
        ->middleware('permission:roles.edit')
        ->name('api.roles.permissions.sync');


  
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('permission:permissions.view')
        ->name('api.permissions.index');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->middleware('permission:permissions.create')
        ->name('api.permissions.store');

    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])
        ->middleware('permission:permissions.edit')
        ->name('api.permissions.update');

    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
        ->middleware('permission:permissions.delete')
        ->name('api.permissions.destroy');


 

    Route::get('/users/{user}/roles', [UserRoleController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('api.users.roles.index');

    Route::put('/users/{user}/roles', [UserRoleController::class, 'sync'])
        ->middleware('permission:users.edit')
        ->name('api.users.roles.sync');
 Route::post('/users/{user}/roles/{role}', [UserRoleController::class, 'assign'])
        ->middleware('permission:users.edit')
        ->name('api.users.roles.assign');

    Route::delete('/users/{user}/roles/{role}', [UserRoleController::class, 'remove'])
        ->middleware('permission:users.edit')
        ->name('api.users.roles.remove');


  

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('api.users.index');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:users.create')
        ->name('api.users.store');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('permission:users.view')
        ->name('api.users.show');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:users.edit')
        ->name('api.users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('api.users.destroy');


        Route::get('/roles/{role}/record-access', [RoleRecordAccessController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('api.roles.record-access.index');
    
    Route::post('/roles/{role}/record-access', [RoleRecordAccessController::class, 'store'])
        ->middleware('permission:roles.edit')
        ->name('api.roles.record-access.store');
    
    Route::put('/record-access/{access}', [RoleRecordAccessController::class, 'update'])
        ->middleware('permission:roles.edit')
        ->name('api.roles.record-access.update');
    
    Route::delete('/record-access/{access}', [RoleRecordAccessController::class, 'destroy'])
        ->middleware('permission:roles.edit')
        ->name('api.roles.record-access.destroy');

        Route::get('/leads', [LeadController::class, 'index'])
    ->middleware('permission:leads.view')
    ->name('api.leads.index');

Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('permission:leads.create')
    ->name('api.leads.store');

    Route::get('/leads/export', [LeadController::class, 'export'])
    ->middleware('permission:leads.export')
    ->name('api.leads.export');

Route::get('/leads/{id}', [LeadController::class, 'show'])
    ->middleware('permission:leads.view')
    ->name('api.leads.show');

Route::put('/leads/{id}', [LeadController::class, 'update'])
    ->middleware('permission:leads.edit')
    ->name('api.leads.update');

Route::delete('/leads/{id}', [LeadController::class, 'destroy'])
    ->middleware('permission:leads.delete')
    ->name('api.leads.destroy');

Route::post('/leads/{id}/restore', [LeadController::class, 'restore'])
    ->middleware('permission:leads.edit')
    ->name('api.leads.restore');

 

});


Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])
    ->name('api.auth.resend-verification-code');