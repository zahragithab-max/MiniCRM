<?php

namespace App\Modules\Quotes\Services;

use App\Modules\Quotes\Models\Quote;
use App\Modules\Settings\System\Services\SystemSettingService;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public function __construct(
        private SystemSettingService $systemSettingService
    ) {}

    public function generateQuoteNumber(): string
    {
        do {
            $quoteNumber = 'QUO-' . random_int(100000, 999999);
        } while (Quote::where('quote_number', $quoteNumber)->exists());

        return $quoteNumber;
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

    public function calculateTotals(Quote $quote): Quote
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

        return $quote->fresh();
    }
}

