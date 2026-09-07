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
use App\Modules\Accounts\Http\Controllers\AccountController;
use App\Modules\Contacts\Http\Controllers\ContactController;
use App\Modules\Deals\Http\Controllers\DealController;
use App\Modules\Deals\Http\Controllers\DealStageController;
use App\Modules\Deals\Http\Controllers\DealNotificationController;
use App\Modules\Deals\Http\Controllers\DealReportController;
use App\Modules\Products\Http\Controllers\ProductController;
use App\Modules\Tasks\Http\Controllers\TaskController;
use App\Modules\Tasks\Http\Controllers\CalendarEventController;


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

    Route::get('/accounts', [AccountController::class, 'index'])
    ->middleware('permission:accounts.view')
    ->name('api.accounts.index');

Route::post('/accounts', [AccountController::class, 'store'])
    ->middleware('permission:accounts.create')
    ->name('api.accounts.store');

    Route::get('/accounts/export', [AccountController::class, 'export'])
    ->middleware('permission:accounts.export')
    ->name('api.accounts.export');


Route::get('/accounts/{id}', [AccountController::class, 'show'])
    ->middleware('permission:accounts.view')
    ->name('api.accounts.show');

Route::put('/accounts/{id}', [AccountController::class, 'update'])
    ->middleware('permission:accounts.edit')
    ->name('api.accounts.update');

Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])
    ->middleware('permission:accounts.delete')
    ->name('api.accounts.destroy');

Route::post('/accounts/{id}/restore', [AccountController::class, 'restore'])
    ->middleware('permission:accounts.edit')
    ->name('api.accounts.restore');

    Route::get('/contacts', [ContactController::class, 'index'])
    ->middleware('permission:contacts.view')
    ->name('api.contacts.index');

Route::post('/contacts', [ContactController::class, 'store'])
    ->middleware('permission:contacts.create')
    ->name('api.contacts.store');

Route::get('/contacts/{id}', [ContactController::class, 'show'])
    ->middleware('permission:contacts.view')
    ->name('api.contacts.show');

Route::put('/contacts/{id}', [ContactController::class, 'update'])
    ->middleware('permission:contacts.edit')
    ->name('api.contacts.update');

Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])
    ->middleware('permission:contacts.delete')
    ->name('api.contacts.destroy');

Route::post('/contacts/{id}/restore', [ContactController::class, 'restore'])
    ->middleware('permission:contacts.edit')
    ->name('api.contacts.restore');

    Route::post('/contacts/{id}/email', [ContactController::class, 'sendEmail'])
    ->middleware('permission:contacts.email')
    ->name('api.contacts.email');

    Route::get('/deals', [DealController::class, 'index'])
    ->middleware('permission:deals.view')
    ->name('api.deals.index');

Route::post('/deals', [DealController::class, 'store'])
    ->middleware('permission:deals.create')
    ->name('api.deals.store');

Route::get('/deals/{id}', [DealController::class, 'show'])
    ->middleware('permission:deals.view')
    ->name('api.deals.show');

Route::put('/deals/{id}', [DealController::class, 'update'])
    ->middleware('permission:deals.edit')
    ->name('api.deals.update');

Route::delete('/deals/{id}', [DealController::class, 'destroy'])
    ->middleware('permission:deals.delete')
    ->name('api.deals.destroy');
    
    Route::get('/deal-stages', [DealStageController::class, 'index'])
    ->middleware('permission:deals.view')
    ->name('api.deal-stages.index');

Route::post('/deal-stages', [DealStageController::class, 'store'])
    ->middleware('permission:deals.create')
    ->name('api.deal-stages.store');

Route::get('/deal-stages/{id}', [DealStageController::class, 'show'])
    ->middleware('permission:deals.view')
    ->name('api.deal-stages.show');

Route::put('/deal-stages/{id}', [DealStageController::class, 'update'])
    ->middleware('permission:deals.edit')
    ->name('api.deal-stages.update');

Route::delete('/deal-stages/{id}', [DealStageController::class, 'destroy'])
    ->middleware('permission:deals.delete')
    ->name('api.deal-stages.destroy');

    Route::get('/deal-notifications', [DealNotificationController::class, 'index'])
    ->name('api.deal-notifications.index');

    Route::get('/products', [ProductController::class, 'index'])
    ->middleware('permission:products.view')
    ->name('api.products.index');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware('permission:products.create')
    ->name('api.products.store');

Route::get('/products/{id}', [ProductController::class, 'show'])
    ->middleware('permission:products.view')
    ->name('api.products.show');

Route::put('/products/{id}', [ProductController::class, 'update'])
    ->middleware('permission:products.edit')
    ->name('api.products.update');

Route::delete('/products/{id}', [ProductController::class, 'destroy'])
    ->middleware('permission:products.delete')
    ->name('api.products.destroy');

    Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware('permission:tasks.view')
    ->name('api.tasks.index');

Route::post('/tasks', [TaskController::class, 'store'])
    ->middleware('permission:tasks.create')
    ->name('api.tasks.store');

Route::get('/tasks/{id}', [TaskController::class, 'show'])
    ->middleware('permission:tasks.view')
    ->name('api.tasks.show');

Route::put('/tasks/{id}', [TaskController::class, 'update'])
    ->middleware('permission:tasks.edit')
    ->name('api.tasks.update');

Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])
    ->middleware('permission:tasks.delete')
    ->name('api.tasks.destroy');

    Route::get('/calendar-events', [CalendarEventController::class, 'index'])
    ->middleware('permission:calendar_events.view')
    ->name('api.calendar-events.index');

Route::post('/calendar-events', [CalendarEventController::class, 'store'])
    ->middleware('permission:calendar_events.create')
    ->name('api.calendar-events.store');

Route::get('/calendar-events/{id}', [CalendarEventController::class, 'show'])
    ->middleware('permission:calendar_events.view')
    ->name('api.calendar-events.show');

Route::put('/calendar-events/{id}', [CalendarEventController::class, 'update'])
    ->middleware('permission:calendar_events.edit')
    ->name('api.calendar-events.update');

Route::delete('/calendar-events/{id}', [CalendarEventController::class, 'destroy'])
    ->middleware('permission:calendar_events.delete')
    ->name('api.calendar-events.destroy');

});


Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])
    ->name('api.auth.resend-verification-code');

    Route::get('/deals/reports/conversion-rate', [
        DealReportController::class,
        'conversionRate',
    ]);