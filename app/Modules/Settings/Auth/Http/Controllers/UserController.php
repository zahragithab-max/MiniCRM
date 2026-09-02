<?php

namespace App\Modules\Settings\Auth\Http\Controllers;

use App\Modules\Settings\Auth\Models\User;
use App\Modules\Settings\Auth\Services\UserService;
use App\Modules\Settings\Auth\Requests\StoreUserRequest;
use App\Modules\Settings\Auth\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'users' => $this->userService->getAll(),
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $this->userService->findById($user->id),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'message' => 'کاربر با موفقیت ایجاد شد.',
            'user' => $user,
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->update($user, $request->validated());

        return response()->json([
            'message' => 'کاربر با موفقیت ویرایش شد.',
            'user' => $user,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'کاربر با موفقیت حذف شد.',
        ]);
    }
}