<?php

namespace Database\Seeders;

use App\Modules\Settings\Notifications\Models\NotificationSetting;
use Illuminate\Database\Seeder;

class NotificationSettingSeeder extends Seeder
{
    public function run(): void
    {
        NotificationSetting::updateOrCreate(
            [
                'event' => 'contact_updated',
                'channel' => 'mail',
            ],
            [
                'is_enabled' => true,
            ]
        );

        NotificationSetting::updateOrCreate(
            [
                'event' => 'invoice_paid',
                'channel' => 'mail',
            ],
            [
                'is_enabled' => true,
            ]
        );
    }
}