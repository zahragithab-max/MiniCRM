<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Auth & Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',

            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.export',

            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'permissions.export',

            // Leads
            'leads.view',
            'leads.create',
            'leads.edit',
            'leads.delete',
            'leads.export',

            // Contacts
            'contacts.view',
            'contacts.create',
            'contacts.edit',
            'contacts.delete',
            'contacts.export',

            // Accounts
            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'accounts.delete',
            'accounts.export',

            // Deals
            'deals.view',
            'deals.create',
            'deals.edit',
            'deals.delete',
            'deals.export',

            // Tasks
            'tasks.view',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.export',

            // Tickets
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
            'tickets.export',

            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.export',

            // Quotes
            'quotes.view',
            'quotes.create',
            'quotes.edit',
            'quotes.delete',
            'quotes.export',

            // Invoices
            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            'invoices.export',

            // Documents
            'documents.view',
            'documents.create',
            'documents.edit',
            'documents.delete',
            'documents.export',

            // Notifications
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',

            // Reports
            'reports.view',
            'reports.export',

            // Settings
            'settings.view',
            'settings.edit',

            // Activity Log
            'activity_logs.view',
            'activity_logs.export',

            // Import / Export
            'imports.view',
            'imports.create',

            'exports.view',
            'exports.create',
        ];

        foreach ($permissions as $permission) {
            [$module, $action] = explode('.', $permission);
        
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                ],
                [
                    'module' => $module,
                    'action' => $action,
                ]
            );
        }
    }
}