<?php

namespace App\Modules\Invoices\Services;

use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Models\StockMovement;
use App\Modules\Products\Models\Product;
use App\Modules\Settings\System\Services\SystemSettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    public function __construct(
        private SystemSettingService $systemSettingService
    ) {}

    public function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'INV-' . random_int(100000, 999999);
        } while (
            Invoice::where('invoice_number', $invoiceNumber)->exists()
        );

        return $invoiceNumber;
    }

    public function prepareVatSettings(array $data): array
    {
        $vatEnabled = $data['vat_enabled'] ?? true;

        $data['vat_enabled'] = $vatEnabled;
        $data['vat_rate'] = $vatEnabled
            ? $this->systemSettingService->getVatRate()
            : 0;

        return $data;
    }

    public function calculateTotals(Invoice $invoice): Invoice
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

        return $invoice->fresh();
    }

    public function issue(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice) {
            if ($invoice->status !== 'draft') {
                throw ValidationException::withMessages([
                    'invoice' => 'Only a draft invoice can be issued.',
                ]);
            }

            $items = $invoice->items()->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'invoice' => 'Cannot issue an invoice with no items.',
                ]);
            }

            foreach ($items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => "Insufficient stock for product \"{$product->name}\".",
                    ]);
                }
            }

            foreach ($items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $item->quantity;

                $product->update([
                    'stock' => $stockAfter,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                ]);
            }

            $invoice->update([
                'status' => 'issued',
            ]);

            return $invoice->fresh(['items.product']);
        });
    }
}