<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Sales Manager',
            'Sales Agent',
            'Support Manager',
            'Support Agent',
            'Accountant',
            'Viewer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
            ]);
        }

        $allPermissions = Permission::pluck('id', 'name');

        Role::where('name', 'Super Admin')
            ->first()
            ?->permissions()
            ->sync($allPermissions->values());

        Role::where('name', 'Admin')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereNotIn('module', [
                    'roles',
                    'permissions',
                ])->pluck('id')
            );

        Role::where('name', 'Sales Manager')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereIn('module', [
                    'leads',
                    'contacts',
                    'accounts',
                    'deals',
                    'tasks',
                    'reports',
                ])->pluck('id')
            );

        Role::where('name', 'Sales Agent')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereIn('module', [
                    'leads',
                    'contacts',
                    'accounts',
                    'deals',
                    'tasks',
                ])->pluck('id')
            );

        Role::where('name', 'Support Manager')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereIn('module', [
                    'tickets',
                    'contacts',
                    'tasks',
                    'reports',
                ])->pluck('id')
            );

        Role::where('name', 'Support Agent')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereIn('module', [
                    'tickets',
                    'tasks',
                ])->pluck('id')
            );

        Role::where('name', 'Accountant')
            ->first()
            ?->permissions()
            ->sync(
                Permission::whereIn('module', [
                    'products',
                    'quotes',
                    'invoices',
                ])->pluck('id')
            );

        Role::where('name', 'Viewer')
            ->first()
            ?->permissions()
            ->sync(
                Permission::where('action', 'view')->pluck('id')
            );
    }
}