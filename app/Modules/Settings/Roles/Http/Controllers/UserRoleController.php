<?php

namespace App\Modules\Settings\Roles\Http\Controllers;

use App\Modules\Settings\Roles\Models\Role;
use App\Modules\Settings\Auth\Models\User;
use App\Modules\Settings\Roles\Services\UserRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRoleController
{
    public function __construct(
        private UserRoleService $userRoleService
    ) {
    }

    public function index(User $user): JsonResponse
    {
        return response()->json([
            'data' => $this->userRoleService->getUserRoles($user),
        ]);
    }

    public function sync(
        Request $request,
        User $user
    ): JsonResponse {
        $request->validate([
            'role_ids' => ['required', 'array'],
            'role_ids.*' => [
                'integer',
                'exists:roles,id',
            ],
        ]);

        $user = $this->userRoleService->syncRoles(
            $user,
            $request->input('role_ids')
        );

        return response()->json([
            'message' => 'نقش‌های کاربر با موفقیت به‌روزرسانی شد.',
            'data' => $user,
        ]);
    }

    public function assign(
        User $user,
        Role $role
    ): JsonResponse {
        $user = $this->userRoleService->assignRole(
            $user,
            $role
        );

        return response()->json([
            'message' => 'نقش با موفقیت به کاربر اختصاص داده شد.',
            'data' => $user,
        ]);
    }

    public function remove(
        User $user,
        Role $role
    ): JsonResponse {
        $user = $this->userRoleService->removeRole(
            $user,
            $role
        );

        return response()->json([
            'message' => 'نقش با موفقیت از کاربر حذف شد.',
            'data' => $user,
        ]);
    }
}