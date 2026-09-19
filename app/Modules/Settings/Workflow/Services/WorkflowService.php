<?php

namespace App\Modules\Settings\Workflow\Services;

use App\Modules\Settings\Workflow\Models\Workflow;

class WorkflowService
{
    public function getAll()
    {
        return Workflow::latest()->get();
    }

    public function create(array $data): Workflow
    {
        return Workflow::create($data);
    }

    public function update(
        Workflow $workflow,
        array $data
    ): Workflow {
        $workflow->update($data);

        return $workflow->fresh();
    }
}