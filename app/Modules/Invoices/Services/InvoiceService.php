<?php

namespace App\Modules\Invoices\Services;

use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Models\StockMovement;
use App\Modules\Products\Models\Product;
use App\Modules\Settings\Currency\Services\CurrencyService;
use App\Modules\Settings\System\Services\SystemSettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    public function __construct(
        private SystemSettingService $systemSettingService,
        private CurrencyService $currencyService
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

            $currencySetting = $this->currencyService->get();

            $invoice->update([
                'status' => 'issued',
                'currency' => $currencySetting->currency,
                'usd_rate_snapshot' => $currencySetting->usd_rate,
            ]);

            return $invoice->fresh(['items.product']);
        });
        
    }

    public function changeCurrency(
        Invoice $invoice,
        string $currency
    ): Invoice {
        if ($invoice->status !== 'issued') {
            throw ValidationException::withMessages([
                'invoice' => 'Only an issued invoice can change currency.',
            ]);
        }
    
        if ($invoice->currency === $currency) {
            return $invoice->fresh(['items.product']);
        }
    
        $usdRate = $invoice->usd_rate_snapshot;
    
        if (!$usdRate || (float) $usdRate <= 0) {
            throw ValidationException::withMessages([
                'currency' => 'This invoice does not have a valid USD exchange rate snapshot.',
            ]);
        }
    
        return DB::transaction(function () use (
            $invoice,
            $currency,
            $usdRate
        ) {
            $fromCurrency = $invoice->currency;
    
            $invoice->update([
                'subtotal' => $this->convertInvoiceAmount(
                    (float) $invoice->subtotal,
                    $fromCurrency,
                    $currency,
                    (float) $usdRate
                ),
                'vat' => $this->convertInvoiceAmount(
                    (float) $invoice->vat,
                    $fromCurrency,
                    $currency,
                    (float) $usdRate
                ),
                'grand_total' => $this->convertInvoiceAmount(
                    (float) $invoice->grand_total,
                    $fromCurrency,
                    $currency,
                    (float) $usdRate
                ),
                'currency' => $currency,
            ]);
    
            foreach ($invoice->items as $item) {
                $item->update([
                    'unit_price' => $this->convertInvoiceAmount(
                        (float) $item->unit_price,
                        $fromCurrency,
                        $currency,
                        (float) $usdRate
                    ),
                    'discount' => $this->convertInvoiceAmount(
                        (float) $item->discount,
                        $fromCurrency,
                        $currency,
                        (float) $usdRate
                    ),
                    'tax' => $this->convertInvoiceAmount(
                        (float) $item->tax,
                        $fromCurrency,
                        $currency,
                        (float) $usdRate
                    ),
                    'total' => $this->convertInvoiceAmount(
                        (float) $item->total,
                        $fromCurrency,
                        $currency,
                        (float) $usdRate
                    ),
                ]);
            }
    
            return $invoice->fresh(['items.product']);
        });
    }
    
    private function convertInvoiceAmount(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        float $usdRate
    ): float {
        if ($fromCurrency === $toCurrency) {
            return round($amount, 2);
        }
    
        $tomanAmount = match ($fromCurrency) {
            'IRT' => $amount,
            'IRR' => $amount / 10,
            'USD' => $amount * $usdRate,
            default => throw new \InvalidArgumentException(
                'Unsupported invoice currency.'
            ),
        };
    
        $convertedAmount = match ($toCurrency) {
            'IRT' => $tomanAmount,
            'IRR' => $tomanAmount * 10,
            'USD' => $tomanAmount / $usdRate,
            default => throw new \InvalidArgumentException(
                'Unsupported invoice currency.'
            ),
        };
    
        return round($convertedAmount, 2);
    }
}