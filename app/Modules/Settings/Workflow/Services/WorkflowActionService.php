<?php

namespace App\Modules\Settings\Workflow\Services;

use App\Modules\Settings\Workflow\Models\Workflow;
use App\Modules\Settings\Workflow\Models\WorkflowAction;

class WorkflowActionService
{
    public function getAll(Workflow $workflow)
    {
        return $workflow->actions()->latest()->get();
    }

    public function create(
        Workflow $workflow,
        array $data
    ): WorkflowAction {
        return $workflow->actions()->create($data);
    }

    public function update(
        WorkflowAction $action,
        array $data
    ): WorkflowAction {
        $action->update($data);

        return $action->fresh();
    }
}