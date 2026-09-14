<?php

namespace App\Modules\Quotes\Services;

use App\Modules\Quotes\Models\Quote;
use App\Modules\Quotes\Models\QuoteItem;
use App\Modules\Products\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuoteItemService
{
    public function addItem(
        Quote $quote,
        array $data
    ): QuoteItem {
        return DB::transaction(function () use ($quote, $data) {
            $product = Product::findOrFail($data['product_id']);

            $quantity = $data['quantity'];
            $unitPrice = $data['unit_price'] ?? $product->price;
            $discount = $data['discount'] ?? 0;

            $subtotal = $quantity * $unitPrice;

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => 'Discount cannot be greater than the item subtotal.',
                ]);
            }

            $taxableAmount = $subtotal - $discount;

            $tax = $quote->vat_enabled
                ? ($taxableAmount * $quote->vat_rate) / 100
                : 0;

            $total = $taxableAmount + $tax;

            $quoteItem = $quote->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
            ]);

            $this->recalculateQuoteTotals($quote);

            return $quoteItem->load('product');
        });
    }

    private function recalculateQuoteTotals(Quote $quote): void
    {
        $items = $quote->items()->get();

        $subtotal = $items->sum(function ($item) {
            return ($item->quantity * $item->unit_price) - $item->discount;
        });

        $vat = $quote->vat_enabled
            ? ($subtotal * $quote->vat_rate) / 100
            : 0;

        $grandTotal = $subtotal + $vat;

        $quote->update([
            'subtotal' => $subtotal,
            'vat' => $vat,
            'grand_total' => $grandTotal,
        ]);
    }
}

