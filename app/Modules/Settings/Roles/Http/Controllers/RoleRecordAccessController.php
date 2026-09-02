<?php

namespace App\Modules\Settings\Roles\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Roles\Models\Role;
use App\Modules\Settings\Roles\Models\RoleRecordAccess;
use App\Modules\Settings\Roles\Services\RoleRecordAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleRecordAccessController extends Controller
{
    public function __construct(
        private RoleRecordAccessService $service
    ) {
    }

    public function index(Role $role): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getForRole($role),
        ]);
    }

    public function store(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'module' => ['required', 'string', 'max:100'],
            'access_level' => ['required', 'string', 'max:50'],
        ]);

        $access = $this->service->create($role, $data);

        return response()->json([
            'message' => 'Record access created successfully.',
            'data' => $access,
        ], 201);
    }

    public function update(
        Request $request,
        RoleRecordAccess $access
    ): JsonResponse {
        $data = $request->validate([
            'module' => ['sometimes', 'string', 'max:100'],
            'access_level' => ['sometimes', 'string', 'max:50'],
        ]);

        $access = $this->service->update($access, $data);

        return response()->json([
            'message' => 'Record access updated successfully.',
            'data' => $access,
        ]);
    }

    public function destroy(RoleRecordAccess $access): JsonResponse
    {
        $this->service->delete($access);

        return response()->json([
            'message' => 'Record access deleted successfully.',
        ]);
    }
}