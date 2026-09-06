<?php

namespace App\Modules\Deals\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Deals\Requests\StoreDealRequest;
use App\Modules\Deals\Requests\UpdateDealRequest;
use App\Modules\Deals\Services\DealService;
use Illuminate\Http\JsonResponse;

class DealController extends Controller
{
    public function __construct(
        private DealService $dealService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->dealService->getAll()
        );
    }

    public function store(StoreDealRequest $request): JsonResponse
    {
        $deal = $this->dealService->create(
            $request->validated()
        );

        return response()->json($deal, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->dealService->findById($id)
        );
    }

    public function update(
        UpdateDealRequest $request,
        int $id
    ): JsonResponse {
        $deal = $this->dealService->findById($id);

        $updatedDeal = $this->dealService->update(
            $deal,
            $request->validated()
        );

        return response()->json($updatedDeal);
    }

    public function destroy(int $id): JsonResponse
    {
        $deal = $this->dealService->findById($id);

        $this->dealService->delete($deal);

        return response()->json([
            'message' => 'Deal deleted successfully.',
        ]);
    }
}