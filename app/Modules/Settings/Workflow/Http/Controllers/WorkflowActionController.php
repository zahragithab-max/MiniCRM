<?php

namespace App\Modules\Settings\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Workflow\Http\Requests\StoreWorkflowActionRequest;
use App\Modules\Settings\Workflow\Http\Requests\UpdateWorkflowActionRequest;
use App\Modules\Settings\Workflow\Models\Workflow;
use App\Modules\Settings\Workflow\Models\WorkflowAction;
use App\Modules\Settings\Workflow\Services\WorkflowActionService;
use Illuminate\Http\JsonResponse;

class WorkflowActionController extends Controller
{
    public function __construct(
        private WorkflowActionService $workflowActionService
    ) {}

    public function index(Workflow $workflow): JsonResponse
    {
        return $this->success(
            $this->workflowActionService->getAll($workflow)
        );
    }

    public function store(
        StoreWorkflowActionRequest $request,
        Workflow $workflow
    ): JsonResponse {
        return $this->success(
            $this->workflowActionService->create(
                $workflow,
                $request->validated()
            ),
            201
        );
    }

    public function update(
        UpdateWorkflowActionRequest $request,
        WorkflowAction $workflowAction
    ): JsonResponse {
        return $this->success(
            $this->workflowActionService->update(
                $workflowAction,
                $request->validated()
            )
        );
    }
}