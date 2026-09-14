<?php

namespace App\Modules\Settings\System\Services;

use App\Modules\Settings\System\Models\SystemSetting;

class SystemSettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return SystemSetting::where('key', $key)->value('value') ?? $default;
    }

    public function set(string $key, mixed $value): SystemSetting
    {
        return SystemSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function getVatRate(): float
    {
        return (float) $this->get('vat_rate', 10);
    }

    public function setVatRate(float $rate): SystemSetting
    {
        return $this->set('vat_rate', $rate);
    }
}