<?php

namespace App\Providers;

use App\Modules\Deals\Events\DealStageChanged;
use App\Modules\Deals\Listeners\RecordDealStageHistory;
use App\Modules\Deals\Listeners\SendDealStageChangedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Event::listen(
            DealStageChanged::class,
            SendDealStageChangedNotification::class
        );

        Event::listen(
            DealStageChanged::class,
            RecordDealStageHistory::class
        );
    }
}