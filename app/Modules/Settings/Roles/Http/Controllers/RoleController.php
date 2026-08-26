<?php

namespace App\Modules\Settings\Roles\Http\Controllers;

use App\Modules\Settings\Roles\Models\Role;
use App\Modules\Settings\Roles\Requests\StoreRoleRequest;
use App\Modules\Settings\Roles\Requests\UpdateRoleRequest;
use App\Modules\Settings\Roles\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController
{
    public function __construct(
        private RoleService $roleService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->roleService->getAll(),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'نقش با موفقیت ایجاد شد.',
            'data' => $role,
        ], 201);
    }

    public function update(
        UpdateRoleRequest $request,
        Role $role
    ): JsonResponse {
        $role = $this->roleService->update(
            $role,
            $request->validated()
        );

        return response()->json([
            'message' => 'نقش با موفقیت به‌روزرسانی شد.',
            'data' => $role,
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role);

        return response()->json([
            'message' => 'نقش با موفقیت حذف شد.',
        ]);
    }

    public function syncPermissions(
        Request $request,
        Role $role
    ): JsonResponse {
        $request->validate([
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $role = $this->roleService->syncPermissions(
            $role,
            $request->input('permission_ids')
        );

        return response()->json([
            'message' => 'دسترسی‌های نقش با موفقیت به‌روزرسانی شد.',
            'data' => $role,
        ]);
    }
}