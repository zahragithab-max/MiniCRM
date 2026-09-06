<?php

namespace App\Modules\Deals\Listeners;

use App\Modules\Deals\Events\DealStageChanged;
use App\Modules\Deals\Models\DealStageHistory;

class RecordDealStageHistory
{
    /**
     * Handle the event.
     */
    public function handle(DealStageChanged $event): void
    {
        DealStageHistory::create([
            'deal_id' => $event->deal->id,
            'from_stage_id' => $event->oldStageId ?: null,
            'to_stage_id' => $event->newStageId,
        ]);
    }
}