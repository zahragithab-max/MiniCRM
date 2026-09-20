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
use App\Modules\Tickets\Http\Controllers\TicketController;
use App\Modules\Tickets\Http\Controllers\TicketMessageController;
use App\Modules\Tickets\Http\Controllers\TicketAttachmentController;
use App\Modules\Tickets\Http\Controllers\TicketSatisfactionController;
use App\Modules\Deals\Http\Controllers\DealProductController;
use App\Modules\Quotes\Http\Controllers\QuoteController;
use App\Modules\Quotes\Http\Controllers\QuoteItemController;
use App\Modules\Settings\System\Http\Controllers\SystemSettingController;
use App\Modules\Invoices\Http\Controllers\InvoiceController;
use App\Modules\Invoices\Http\Controllers\InvoiceItemController;
use App\Modules\Documents\Http\Controllers\DocumentController;
use App\Modules\Settings\Notifications\Http\Controllers\NotificationSettingController;
use App\Modules\Settings\Workflow\Http\Controllers\WorkflowController;
use App\Modules\Settings\Workflow\Http\Controllers\WorkflowActionController;
use App\Modules\Settings\Notifications\Http\Controllers\NotificationRuleController;
use App\Modules\Settings\Notifications\Http\Controllers\NotificationRuleRecipientController;
use App\Modules\Settings\Notifications\Http\Controllers\NotificationRuleChannelController;
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\Dashboard\Http\Controllers\WidgetController;
use App\Modules\Settings\Company\Http\Controllers\CompanySettingController;
use App\Modules\Settings\Currency\Http\Controllers\CurrencySettingController;


/*
|--------------------------------------------------------------------------
| Public Auth Routes (no auth:sanctum)
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->name('api.auth.')->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/verify-email', 'verifyEmail')->name('verify-email');
    Route::post('/login', 'login')->name('login');
    Route::post('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/reset-password', 'resetPassword')->name('reset-password');
    Route::post('/resend-verification-code', 'resendVerificationCode')->name('resend-verification-code');
});


Route::middleware('auth:sanctum')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Current User
    |----------------------------------------------------------------------
    */

    Route::get('/user', function (Request $request) {
        return response()->json([
            'message' => 'USER ROUTE OK',
            'user' => $request->user(),
        ]);
    })->name('api.user');


    /*
    |----------------------------------------------------------------------
    | Roles, Permissions & Record Access
    |----------------------------------------------------------------------
    */

    Route::prefix('roles')->name('api.roles.')->group(function () {

        Route::controller(RoleController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:roles.view')->name('index');
            Route::post('/', 'store')->middleware('permission:roles.create')->name('store');
            Route::put('/{role}', 'update')->middleware('permission:roles.edit')->name('update');
            Route::delete('/{role}', 'destroy')->middleware('permission:roles.delete')->name('destroy');
            Route::put('/{role}/permissions', 'syncPermissions')->middleware('permission:roles.edit')->name('permissions.sync');
        });

        Route::controller(RoleRecordAccessController::class)->prefix('{role}/record-access')->name('record-access.')->group(function () {
            Route::get('/', 'index')->middleware('permission:roles.view')->name('index');
            Route::post('/', 'store')->middleware('permission:roles.edit')->name('store');
        });
    });

    Route::controller(RoleRecordAccessController::class)->prefix('record-access')->name('api.roles.record-access.')->group(function () {
        Route::put('/{access}', 'update')->middleware('permission:roles.edit')->name('update');
        Route::delete('/{access}', 'destroy')->middleware('permission:roles.edit')->name('destroy');
    });

    Route::controller(PermissionController::class)->prefix('permissions')->name('api.permissions.')->group(function () {
        Route::get('/', 'index')->middleware('permission:permissions.view')->name('index');
        Route::post('/', 'store')->middleware('permission:permissions.create')->name('store');
        Route::put('/{permission}', 'update')->middleware('permission:permissions.edit')->name('update');
        Route::delete('/{permission}', 'destroy')->middleware('permission:permissions.delete')->name('destroy');
    });


    /*
    |----------------------------------------------------------------------
    | Users
    |----------------------------------------------------------------------
    */

    Route::prefix('users')->name('api.users.')->group(function () {

        Route::controller(UserController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:users.view')->name('index');
            Route::post('/', 'store')->middleware('permission:users.create')->name('store');
            Route::get('/{user}', 'show')->middleware('permission:users.view')->name('show');
            Route::put('/{user}', 'update')->middleware('permission:users.edit')->name('update');
            Route::delete('/{user}', 'destroy')->middleware('permission:users.delete')->name('destroy');
        });

        Route::controller(UserRoleController::class)->prefix('{user}/roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->middleware('permission:users.view')->name('index');
            Route::put('/', 'sync')->middleware('permission:users.edit')->name('sync');
            Route::post('/{role}', 'assign')->middleware('permission:users.edit')->name('assign');
            Route::delete('/{role}', 'remove')->middleware('permission:users.edit')->name('remove');
        });

        
    });

    Route::controller(UserRoleController::class)->prefix('{user}/roles')->name('roles.')->group(function () {
        Route::get('/', 'index')->middleware('permission:users.view')->name('index');
        Route::put('/', 'sync')->middleware('permission:users.edit')->name('sync');
        Route::post('/{role}', 'assign')->middleware('permission:users.edit')->name('assign');
        Route::delete('/{role}', 'remove')->middleware('permission:users.edit')->name('remove');
    });

    Route::controller(DocumentController::class)->prefix('documents')->name('api.documents.')->group(function () {
        Route::get('/{id}/download', 'download')->middleware('permission:documents.view')->name('download');
        Route::post('/{id}/copy', 'copy')->middleware('permission:documents.create')->name('copy');
        Route::get('/{type}/{id}', 'index')->middleware('permission:documents.view')->name('index');
        Route::post('/{type}/{id}', 'store')->middleware('permission:documents.create')->name('store');
    });
    
    /*
    |----------------------------------------------------------------------
    | Leads
    |----------------------------------------------------------------------
    */

    Route::controller(LeadController::class)->prefix('leads')->name('api.leads.')->group(function () {
        Route::get('/', 'index')->middleware('permission:leads.view')->name('index');
        Route::post('/', 'store')->middleware('permission:leads.create')->name('store');
        Route::get('/export', 'export')->middleware('permission:leads.export')->name('export'); // must stay before /{id}
        Route::get('/{id}', 'show')->middleware('permission:leads.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:leads.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:leads.delete')->name('destroy');
        Route::post('/{id}/restore', 'restore')->middleware('permission:leads.edit')->name('restore');
    });


    /*
    |----------------------------------------------------------------------
    | Accounts
    |----------------------------------------------------------------------
    */

    Route::controller(AccountController::class)->prefix('accounts')->name('api.accounts.')->group(function () {
        Route::get('/', 'index')->middleware('permission:accounts.view')->name('index');
        Route::post('/', 'store')->middleware('permission:accounts.create')->name('store');
        Route::get('/export', 'export')->middleware('permission:accounts.export')->name('export'); // must stay before /{id}
        Route::get('/{id}', 'show')->middleware('permission:accounts.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:accounts.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:accounts.delete')->name('destroy');
        Route::post('/{id}/restore', 'restore')->middleware('permission:accounts.edit')->name('restore');
    });


    /*
    |----------------------------------------------------------------------
    | Contacts
    |----------------------------------------------------------------------
    */

    Route::controller(ContactController::class)->prefix('contacts')->name('api.contacts.')->group(function () {
        Route::get('/', 'index')->middleware('permission:contacts.view')->name('index');
        Route::post('/', 'store')->middleware('permission:contacts.create')->name('store');
        Route::get('/{id}', 'show')->middleware('permission:contacts.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:contacts.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:contacts.delete')->name('destroy');
        Route::post('/{id}/restore', 'restore')->middleware('permission:contacts.edit')->name('restore');
        Route::post('/{id}/email', 'sendEmail')->middleware('permission:contacts.email')->name('email');
    });


    /*
    |----------------------------------------------------------------------
    | Deals (deals, stages, notifications, deal-products)
    |----------------------------------------------------------------------
    */

    Route::prefix('deals')->name('api.deals.')->group(function () {

        Route::controller(DealController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:deals.view')->name('index');
            Route::post('/', 'store')->middleware('permission:deals.create')->name('store');
            Route::get('/{id}', 'show')->middleware('permission:deals.view')->name('show');
            Route::put('/{id}', 'update')->middleware('permission:deals.edit')->name('update');
            Route::delete('/{id}', 'destroy')->middleware('permission:deals.delete')->name('destroy');
        });

        Route::controller(DealProductController::class)->prefix('{dealId}/products')->name('products.')->group(function () {
            Route::post('/', 'store')->middleware('permission:deals.edit')->name('store');
            Route::get('/', 'index')->middleware('permission:deals.view')->name('index');
        });
    });

    Route::controller(DealStageController::class)->prefix('deal-stages')->name('api.deal-stages.')->group(function () {
        Route::get('/', 'index')->middleware('permission:deals.view')->name('index');
        Route::post('/', 'store')->middleware('permission:deals.create')->name('store');
        Route::get('/{id}', 'show')->middleware('permission:deals.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:deals.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:deals.delete')->name('destroy');
    });

    Route::get('/deal-notifications', [DealNotificationController::class, 'index'])
        ->name('api.deal-notifications.index');


    /*
    |----------------------------------------------------------------------
    | Products
    |----------------------------------------------------------------------
    */

    Route::controller(ProductController::class)->prefix('products')->name('api.products.')->group(function () {
        Route::get('/', 'index')->middleware('permission:products.view')->name('index');
        Route::post('/', 'store')->middleware('permission:products.create')->name('store');
        Route::get('/{id}', 'show')->middleware('permission:products.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:products.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:products.delete')->name('destroy');
    });


    /*
    |----------------------------------------------------------------------
    | Tasks & Calendar Events
    |----------------------------------------------------------------------
    */

    Route::controller(TaskController::class)->prefix('tasks')->name('api.tasks.')->group(function () {
        Route::get('/', 'index')->middleware('permission:tasks.view')->name('index');
        Route::post('/', 'store')->middleware('permission:tasks.create')->name('store');
        Route::get('/{id}', 'show')->middleware('permission:tasks.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:tasks.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:tasks.delete')->name('destroy');
    });

    Route::controller(CalendarEventController::class)->prefix('calendar-events')->name('api.calendar-events.')->group(function () {
        Route::get('/', 'index')->middleware('permission:calendar_events.view')->name('index');
        Route::post('/', 'store')->middleware('permission:calendar_events.create')->name('store');
        Route::get('/{id}', 'show')->middleware('permission:calendar_events.view')->name('show');
        Route::put('/{id}', 'update')->middleware('permission:calendar_events.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('permission:calendar_events.delete')->name('destroy');
    });

    Route::controller(NotificationSettingController::class)
    ->prefix('notification-settings')
    ->name('api.notification-settings.')
    ->group(function () {
        Route::get('/')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::put('/{notificationSetting}')
            ->middleware('permission:notifications.edit')
            ->name('update');
    });

    Route::controller(WorkflowController::class)
    ->prefix('workflows')
    ->name('api.workflows.')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/', 'store')
            ->middleware('permission:notifications.create')
            ->name('store');

        Route::put('/{workflow}', 'update')
            ->middleware('permission:notifications.edit')
            ->name('update');
    });

Route::controller(WorkflowActionController::class)
    ->prefix('workflows/{workflow}/actions')
    ->name('api.workflow-actions.')
    ->group(function () {
        Route::get('/')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/')
            ->middleware('permission:notifications.create')
            ->name('store');
    });

Route::put(
    '/workflow-actions/{workflowAction}',
    [WorkflowActionController::class, 'update']
)
    ->middleware('permission:notifications.edit')
    ->name('api.workflow-actions.update');

    Route::controller(NotificationRuleController::class)
    ->prefix('notification-rules')
    ->name('api.notification-rules.')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/', 'store')
            ->middleware('permission:notifications.create')
            ->name('store');

        Route::put('/{notificationRule}', 'update')
            ->middleware('permission:notifications.edit')
            ->name('update');
    });

    Route::controller(NotificationRuleRecipientController::class)
    ->prefix('notification-rules/{notificationRule}/recipients')
    ->name('api.notification-rule-recipients.')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/', 'store')
            ->middleware('permission:notifications.create')
            ->name('store');
    });

Route::put(
    '/notification-rule-recipients/{recipient}',
    [NotificationRuleRecipientController::class, 'update']
)
    ->middleware('permission:notifications.edit')
    ->name('api.notification-rule-recipients.update');

    Route::controller(NotificationRuleChannelController::class)
    ->prefix('notification-rules/{notificationRule}/channels')
    ->name('api.notification-rule-channels.')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('permission:notifications.view')
            ->name('index');

        Route::post('/', 'store')
            ->middleware('permission:notifications.create')
            ->name('store');
    });

Route::put(
    '/notification-rule-channels/{channel}',
    [NotificationRuleChannelController::class, 'update']
)
    ->middleware('permission:notifications.edit')
    ->name('api.notification-rule-channels.update');

    /*
    |----------------------------------------------------------------------
    | Tickets (tickets, messages, attachments, satisfaction)
    |----------------------------------------------------------------------
    */

    Route::prefix('tickets')->name('api.tickets.')->group(function () {

        Route::controller(TicketController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:tickets.view')->name('index');
            Route::post('/', 'store')->middleware('permission:tickets.create')->name('store');
            Route::get('/{id}', 'show')->middleware('permission:tickets.view')->name('show');
            Route::put('/{id}', 'update')->middleware('permission:tickets.edit')->name('update');
            Route::delete('/{id}', 'destroy')->middleware('permission:tickets.delete')->name('destroy');
        });

        Route::controller(TicketMessageController::class)->prefix('{ticketId}/messages')->name('messages.')->group(function () {
            Route::get('/', 'index')->middleware('permission:tickets.view')->name('index');
            Route::post('/', 'store')->middleware('permission:tickets.edit')->name('store');
        });

        Route::post('/{ticketId}/satisfaction', [TicketSatisfactionController::class, 'store'])
            ->middleware('permission:tickets.edit')
            ->name('satisfaction.store');
    });

    Route::post('/ticket-messages/{messageId}/attachments', [TicketAttachmentController::class, 'store'])
        ->middleware('permission:tickets.edit')
        ->name('api.ticket-messages.attachments.store');


    /*
    |----------------------------------------------------------------------
    | Quotes
    |----------------------------------------------------------------------
    */

    Route::prefix('quotes')->name('api.quotes.')->group(function () {

        Route::controller(QuoteController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:quotes.view')->name('index');
            Route::post('/', 'store')->middleware('permission:quotes.create')->name('store');
            Route::get('/{id}', 'show')->middleware('permission:quotes.view')->name('show');
        });

        Route::controller(QuoteItemController::class)->prefix('{quoteId}/items')->name('items.')->group(function () {
            Route::get('/', 'index')->middleware('permission:quotes.view')->name('index');
            Route::post('/', 'store')->middleware('permission:quotes.edit')->name('store');
        });
    });


    /*
    |----------------------------------------------------------------------
    | Settings
    |----------------------------------------------------------------------
    */

    Route::controller(SystemSettingController::class)->prefix('settings/vat')->name('api.settings.')->group(function () {
        Route::get('/', 'vat')->name('vat');
        Route::put('/', 'updateVat')->name('vat.update');
    });

    Route::controller(DashboardController::class)
    ->prefix('dashboard')
    ->name('api.dashboard.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(CompanySettingController::class)
    ->prefix('settings/company')
    ->name('api.settings.company.')
    ->group(function () {
        Route::get('/', 'show')->name('show');

        Route::put('/', 'update')->name('update');

        Route::post('/logo', 'uploadLogo')->name('logo.upload');

        Route::delete('/logo', 'deleteLogo')->name('logo.delete');
    });

    Route::controller(CurrencySettingController::class)
    ->prefix('settings/currency')
    ->name('api.settings.currency.')
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
        Route::post('/convert', 'convert')->name('convert');
    });

});


Route::controller(WidgetController::class)
    ->prefix('dashboard/widgets')
    ->name('api.dashboard.widgets.')
    ->group(function () {
        Route::post('/', 'show')->name('show');
    });


/*
|--------------------------------------------------------------------------
| Routes NOT wrapped in auth:sanctum (kept exactly as in the original file —
| see note below the file about these being unprotected)
|--------------------------------------------------------------------------
*/

Route::get('/deals/reports/conversion-rate', [DealReportController::class, 'conversionRate']);

Route::prefix('invoices')->name('api.invoices.')->group(function () {

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/issue', 'issue')->name('issue');
        Route::get('/{id}/pdf', 'pdf')->name('pdf');
        Route::put('/{id}/currency', 'changeCurrency')->name('change-currency');
    });

    Route::controller(InvoiceItemController::class)->prefix('{invoiceId}/items')->name('items.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{itemId}', 'update')->name('update');
    });
});


