<?php

namespace App\Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Requests\StoreProductRequest;
use App\Modules\Products\Http\Requests\UpdateProductRequest;
use App\Modules\Products\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->productService->getAll()
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->validated()
        );

        return response()->json($product, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->productService->findById($id)
        );
    }

    public function update(
        UpdateProductRequest $request,
        int $id
    ): JsonResponse {
        $product = $this->productService->findById($id);

        $updatedProduct = $this->productService->update(
            $product,
            $request->validated()
        );

        return response()->json($updatedProduct);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        $this->productService->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}