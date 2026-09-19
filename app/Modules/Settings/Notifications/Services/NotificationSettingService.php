<?php

namespace App\Modules\Settings\Notifications\Services;

use App\Modules\Settings\Notifications\Models\NotificationSetting;

class NotificationSettingService
{
    public function getAll()
    {
        return NotificationSetting::latest()->get();
    }

    public function update(
        NotificationSetting $setting,
        array $data
    ): NotificationSetting {
        $setting->update($data);

        return $setting->fresh();
    }
}