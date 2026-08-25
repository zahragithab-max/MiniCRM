<?php

namespace App\Modules\Roles\Services;

use App\Models\User;
use App\Models\Role;

class UserRoleService
{
    public function getUserRoles(User $user)
    {
        return $user->roles()->get();
    }

    public function syncRoles(User $user, array $roleIds): User
    {
        $user->roles()->sync($roleIds);

        return $user->fresh('roles');
    }

    public function assignRole(User $user, Role $role): User
    {
        $user->roles()->syncWithoutDetaching([
            $role->id,
        ]);

        return $user->fresh('roles');
    }

    public function removeRole(User $user, Role $role): User
    {
        $user->roles()->detach($role->id);

        return $user->fresh('roles');
    }
}