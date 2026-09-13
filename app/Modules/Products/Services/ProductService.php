<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Product::latest()->paginate($perPage);
    }

    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(
        array $data,
        ?UploadedFile $image = null
    ): Product {
        if ($image) {
            $data['image'] = $image->store('products');
        }

        return Product::create($data);
    }

    public function update(
        Product $product,
        array $data,
        ?UploadedFile $image = null
    ): Product {
        if ($image) {
            $data['image'] = $image->store('products');
        }

        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}