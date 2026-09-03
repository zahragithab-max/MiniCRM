<?php

namespace App\Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Accounts\Requests\StoreAccountRequest;
use App\Modules\Accounts\Requests\UpdateAccountRequest;
use App\Modules\Accounts\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $accountService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);

        return response()->json(
            $this->accountService->getAll($perPage)
        );
    }

    public function store(StoreAccountRequest $request): JsonResponse
    {
        $account = $this->accountService->create(
            $request->validated()
        );

        return response()->json($account, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->accountService->findById($id)
        );
    }

    public function update(
        UpdateAccountRequest $request,
        int $id
    ): JsonResponse {
        $account = $this->accountService->findById($id);

        $updatedAccount = $this->accountService->update(
            $account,
            $request->validated()
        );

        return response()->json($updatedAccount);
    }

    public function destroy(int $id): JsonResponse
    {
        $account = $this->accountService->findById($id);

        $this->accountService->delete($account);

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    return $this->accountService->export();
}

    public function restore(int $id): JsonResponse
    {
        $account = $this->accountService->restore($id);

        return response()->json($account);
    }
}