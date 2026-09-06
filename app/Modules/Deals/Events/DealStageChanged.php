<?php

namespace App\Modules\Deals\Events;

use App\Modules\Deals\Models\Deal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DealStageChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Deal $deal,
        public int $oldStageId,
        public int $newStageId
    ) {
    }
}