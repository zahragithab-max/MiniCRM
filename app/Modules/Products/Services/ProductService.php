<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use App\Modules\Settings\Currency\Services\CurrencyService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function __construct(
        private CurrencyService $currencyService
    ) {
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        $products = Product::latest()->paginate($perPage);

        $products->getCollection()->transform(
            fn (Product $product) => $this->addCurrencyData($product)
        );

        return $products;
    }

    public function findById(int $id): Product
    {
        return $this->addCurrencyData(
            Product::findOrFail($id)
        );
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

    private function addCurrencyData(Product $product): Product
    {
        $setting = $this->currencyService->get();

        $product->setAttribute(
            'currency',
            $setting->currency
        );

        $product->setAttribute(
            'converted_price',
            $this->currencyService->convertFromToman(
                (float) $product->price,
                $setting->currency
            )
        );

        return $product;
    }
}