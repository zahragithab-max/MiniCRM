<?php

namespace App\Modules\Settings\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Workflow\Http\Requests\StoreWorkflowRequest;
use App\Modules\Settings\Workflow\Http\Requests\UpdateWorkflowRequest;
use App\Modules\Settings\Workflow\Models\Workflow;
use App\Modules\Settings\Workflow\Services\WorkflowService;
use Illuminate\Http\JsonResponse;

class WorkflowController extends Controller
{
    public function __construct(
        private WorkflowService $workflowService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            $this->workflowService->getAll()
        );
    }

    public function store(StoreWorkflowRequest $request): JsonResponse
    {
        return $this->success(
            $this->workflowService->create(
                $request->validated()
            ),
            201
        );
    }

    public function update(
        UpdateWorkflowRequest $request,
        Workflow $workflow
    ): JsonResponse {
        return $this->success(
            $this->workflowService->update(
                $workflow,
                $request->validated()
            )
        );
    }
}