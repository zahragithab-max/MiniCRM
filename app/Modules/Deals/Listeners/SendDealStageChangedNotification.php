<?php

namespace App\Modules\Deals\Listeners;

use App\Modules\Deals\Events\DealStageChanged;
use App\Modules\Settings\Workflow\Services\WorkflowRunnerService;

class SendDealStageChangedNotification
{
    public function __construct(
        private WorkflowRunnerService $workflowRunner
    ) {}

    public function handle(DealStageChanged $event): void
    {
        $this->workflowRunner->run(
            'deal_stage_changed',
            [
                'deal' => $event->deal,
                'old_stage_id' => $event->oldStageId,
                'new_stage_id' => $event->newStageId,
            ]
        );
    }
}