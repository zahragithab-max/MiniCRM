<?php

namespace App\Modules\Roles\Controllers;

use App\Models\Permission;
use App\Modules\Roles\Requests\StorePermissionRequest;
use App\Modules\Roles\Requests\UpdatePermissionRequest;
use App\Modules\Roles\Services\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController
{
    public function __construct(
        private PermissionService $permissionService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->permissionService->getAll(),
        ]);
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'دسترسی با موفقیت ایجاد شد.',
            'data' => $permission,
        ], 201);
    }

    public function update(
        UpdatePermissionRequest $request,
        Permission $permission
    ): JsonResponse {
        $permission = $this->permissionService->update(
            $permission,
            $request->validated()
        );

        return response()->json([
            'message' => 'دسترسی با موفقیت به‌روزرسانی شد.',
            'data' => $permission,
        ]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->permissionService->delete($permission);

        return response()->json([
            'message' => 'دسترسی با موفقیت حذف شد.',
        ]);
    }
}