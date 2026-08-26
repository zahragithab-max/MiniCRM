<?php

namespace App\Modules\Settings\Roles\Services;

use App\Modules\Settings\Roles\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getAll(): Collection
    {
        return Permission::with('roles')->get();
    }

    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);

        return $permission->fresh('roles');
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}