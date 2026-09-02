<?php

namespace App\Modules\Settings\Roles\Services;

use App\Modules\Settings\Roles\Models\Role;
use App\Modules\Settings\Roles\Models\RoleRecordAccess;
use Illuminate\Database\Eloquent\Collection;

class RoleRecordAccessService
{
    public function getForRole(Role $role): Collection
    {
        return RoleRecordAccess::where('role_id', $role->id)->get();
    }

    public function create(Role $role, array $data): RoleRecordAccess
    {
        return RoleRecordAccess::create([
            'role_id' => $role->id,
            'module' => $data['module'],
            'access_level' => $data['access_level'],
        ]);
    }

    public function update(
        RoleRecordAccess $access,
        array $data
    ): RoleRecordAccess {
        $access->update($data);

        return $access->fresh();
    }

    public function delete(RoleRecordAccess $access): void
    {
        $access->delete();
    }
}