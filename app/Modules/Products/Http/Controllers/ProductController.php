<?php

namespace App\Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Requests\StoreProductRequest;
use App\Modules\Products\Http\Requests\UpdateProductRequest;
use App\Modules\Products\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    public function index(): JsonResponse
    {
        return $this->success(
            $this->productService->getAll()
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        /** @var UploadedFile|null $image */
        $image = $request->file('image');

        $product = $this->productService->create(
            $request->validated(),
            $image
        );

        return $this->success($product, 201);
    }

    public function show(int $id): JsonResponse
    {

        return $this->success(
            $this->productService->findById($id)
        );
    }

    public function update(
        UpdateProductRequest $request,
        int $id
    ): JsonResponse {
        $product = $this->productService->findById($id);

        /** @var UploadedFile|null $image */
        $image = $request->file('image');

        $updatedProduct = $this->productService->update(
            $product,
            $request->validated(),
            $image
        );

        return $this->success($updatedProduct);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        $this->productService->delete($product);

        return $this->success([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
