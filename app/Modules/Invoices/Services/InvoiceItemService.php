<?php

namespace App\Modules\Invoices\Services;

use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Models\InvoiceItem;
use App\Modules\Products\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceItemService
{
    public function addItem(
        Invoice $invoice,
        array $data
    ): InvoiceItem {
        return DB::transaction(function () use ($invoice, $data) {
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

            $tax = $invoice->vat_enabled
                ? ($taxableAmount * $invoice->vat_rate) / 100
                : 0;

            $total = $taxableAmount + $tax;

            $invoiceItem = $invoice->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
            ]);

            $this->recalculateInvoiceTotals($invoice);

            return $invoiceItem->load('product');
        });
    }

    private function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $items = $invoice->items()->get();

        $subtotal = $items->sum(function ($item) {
            return ($item->quantity * $item->unit_price)
                - $item->discount;
        });

        $vat = $invoice->vat_enabled
            ? ($subtotal * $invoice->vat_rate) / 100
            : 0;

        $grandTotal = $subtotal + $vat;

        $invoice->update([
            'subtotal' => $subtotal,
            'vat' => $vat,
            'grand_total' => $grandTotal,
        ]);
    }
}