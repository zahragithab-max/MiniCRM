<?php

namespace App\Modules\Roles\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function getAll(): Collection
    {
        return Role::with('permissions')->get();
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);

        return $role->fresh('permissions');
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function syncPermissions(
        Role $role,
        array $permissionIds
    ): Role {
        $role->permissions()->sync($permissionIds);

        return $role->fresh('permissions');
    }
}