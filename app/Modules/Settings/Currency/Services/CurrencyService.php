<?php

namespace App\Modules\Settings\Currency\Services;

use App\Modules\Settings\Currency\Models\CurrencySetting;
use App\Modules\Settings\Currency\Models\CurrencyRate;

class CurrencyService
{
    public function get(): CurrencySetting
    {
        $setting = CurrencySetting::query()->first();

        if (!$setting) {
            $setting = CurrencySetting::create([
                'currency' => 'IRT',
                'usd_rate' => null,
            ]);
        }

        return $setting;
    }

    public function update(string $currency, ?float $usdRate = null): CurrencySetting
    {
        $setting = $this->get();
    
        $oldUsdRate = $setting->usd_rate;
    
        $setting->update([
            'currency' => $currency,
            'usd_rate' => $usdRate,
        ]);
    
        if (
            $usdRate !== null &&
            (float) $oldUsdRate !== $usdRate
        ) {
            $this->recordRate(
                'USD',
                'IRT',
                $usdRate
            );
        }
    
        return $setting->fresh();
    }

    public function convertFromToman(
        float $amount,
        string $currency
    ): float {
        return match ($currency) {
            'IRT' => $amount,

            'IRR' => $amount * 10,

            'USD' => $this->convertTomanToUsd($amount),

            default => throw new \InvalidArgumentException(
                'Unsupported currency.'
            ),
        };
    }

    public function recordRate(
        string $fromCurrency,
        string $toCurrency,
        float $rate
    ): CurrencyRate {
        return CurrencyRate::create([
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'rate' => $rate,
            'effective_at' => now(),
        ]);
    }

    public function convertToToman(
        float $amount,
        string $currency
    ): float {
        return match ($currency) {
            'IRT' => $amount,

            'IRR' => $amount / 10,

            'USD' => $this->convertUsdToToman($amount),

            default => throw new \InvalidArgumentException(
                'Unsupported currency.'
            ),
        };
    }

    private function convertTomanToUsd(float $amount): float
    {
        $usdRate = $this->get()->usd_rate;

        if (!$usdRate || $usdRate <= 0) {
            throw new \InvalidArgumentException(
                'USD rate is not configured.'
            );
        }

        return $amount / (float) $usdRate;
    }

    private function convertUsdToToman(float $amount): float
    {
        $usdRate = $this->get()->usd_rate;

        if (!$usdRate || $usdRate <= 0) {
            throw new \InvalidArgumentException(
                'USD rate is not configured.'
            );
        }

        return $amount * (float) $usdRate;
    }
}