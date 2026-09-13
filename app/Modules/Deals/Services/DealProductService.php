<?php

namespace App\Modules\Deals\Services;

use App\Modules\Deals\Models\Deal;
use App\Modules\Deals\Models\DealProduct;
use App\Modules\Products\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DealProductService
{
    public function addProduct(
        Deal $deal,
        array $data
    ): DealProduct {
        return DB::transaction(function () use ($deal, $data) {
            $product = Product::findOrFail($data['product_id']);

            $alreadyExists = $deal->products()
                ->where('product_id', $product->id)
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'product_id' => 'This product is already added to the deal.',
                ]);
            }

            $quantity = $data['quantity'];
            $unitPrice = $data['unit_price'] ?? $product->price;
            $discountType = $data['discount_type'] ?? 'fixed';
            $discount = $data['discount'] ?? 0;

            $subtotal = $quantity * $unitPrice;

            if ($discountType === 'percentage') {
                if ($discount > 100) {
                    throw ValidationException::withMessages([
                        'discount' => 'Percentage discount cannot be greater than 100.',
                    ]);
                }

                $discountAmount = ($subtotal * $discount) / 100;
            } else {
                $discountAmount = $discount;
            }

            if ($discountAmount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => 'Discount cannot be greater than the line subtotal.',
                ]);
            }

            $lineTotal = $subtotal - $discountAmount;

            $dealProduct = $deal->products()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_type' => $discountType,
                'discount' => $discount,
                'line_total' => $lineTotal,
            ]);

            $this->recalculateDealAmount($deal);

            return $dealProduct->load('product');
        });
    }

    private function recalculateDealAmount(Deal $deal): void
    {
        $total = $deal->products()->sum('line_total');

        $deal->update([
            'amount' => $total,
        ]);
    }
}