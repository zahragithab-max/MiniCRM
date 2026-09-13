<?php

namespace App\Modules\Deals\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Deals\Models\Deal;
use App\Modules\Deals\Requests\AddDealProductRequest;
use App\Modules\Deals\Services\DealProductService;
use Illuminate\Http\JsonResponse;

class DealProductController extends Controller
{
    public function __construct(
        private DealProductService $dealProductService
    ) {
    }

    public function index(int $dealId): JsonResponse
    {
        $deal = Deal::with('products.product')->findOrFail($dealId);

        return $this->success($deal->products);
    }

    public function store(
        AddDealProductRequest $request,
        int $dealId
    ): JsonResponse {
        $deal = Deal::findOrFail($dealId);

        $dealProduct = $this->dealProductService->addProduct(
            $deal,
            $request->validated()
        );

        return $this->success($dealProduct, 201);
    }
}