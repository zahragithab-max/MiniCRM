<?php

namespace App\Modules\Deals\Listeners;

use App\Modules\Deals\Events\DealStageChanged;
use App\Modules\Deals\Notifications\DealStageChangedNotification;

class SendDealStageChangedNotification
{
    public function handle(DealStageChanged $event): void
    {
        $manager = $event->deal->owner?->manager;

        if ($manager) {
            $manager->notify(
                new DealStageChangedNotification(
                    $event->deal,
                    $event->oldStageId,
                    $event->newStageId
                )
            );
        }
    }
}