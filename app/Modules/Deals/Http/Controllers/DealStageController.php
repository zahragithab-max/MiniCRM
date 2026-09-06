<?php

namespace App\Modules\Deals\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Deals\Requests\StoreDealStageRequest;
use App\Modules\Deals\Requests\UpdateDealStageRequest;
use App\Modules\Deals\Services\DealStageService;
use Illuminate\Http\JsonResponse;

class DealStageController extends Controller
{
    public function __construct(
        private DealStageService $dealStageService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->dealStageService->getAll()
        );
    }

    public function store(StoreDealStageRequest $request): JsonResponse
    {
        $stage = $this->dealStageService->create(
            $request->validated()
        );

        return response()->json($stage, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->dealStageService->findById($id)
        );
    }

    public function update(
        UpdateDealStageRequest $request,
        int $id
    ): JsonResponse {
        $stage = $this->dealStageService->findById($id);

        $updatedStage = $this->dealStageService->update(
            $stage,
            $request->validated()
        );

        return response()->json($updatedStage);
    }

    public function destroy(int $id): JsonResponse
    {
        $stage = $this->dealStageService->findById($id);

        $this->dealStageService->delete($stage);

        return response()->json([
            'message' => 'Deal stage deleted successfully.',
        ]);
    }
}