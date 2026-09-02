<?php

namespace App\Modules\Settings\Roles\Services;

use App\Modules\Settings\Roles\Models\Role;
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

    public function getRecordAccess(Role $role): Collection
    {
        return $role->recordAccesses()->get();
    }

    public function syncRecordAccess(
        Role $role,
        array $accesses
    ): Role {
        foreach ($accesses as $access) {
            $role->recordAccesses()->updateOrCreate(
                [
                    'module' => $access['module'],
                ],
                [
                    'access_level' => $access['access_level'],
                ]
            );
        }

        return $role->fresh('recordAccesses');
    }
}