<?php

namespace App\Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Http\Requests\StoreTaskRequest;
use App\Modules\Tasks\Http\Requests\UpdateTaskRequest;
use App\Modules\Tasks\Models\Task;
use App\Modules\Tasks\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->taskService->getAll()
        );
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json($task, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->taskService->findById($id)
        );
    }

    public function update(
        UpdateTaskRequest $request,
        int $id
    ): JsonResponse {
        $task = $this->taskService->findById($id);

        $updatedTask = $this->taskService->update(
            $task,
            $request->validated()
        );

        return response()->json($updatedTask);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = $this->taskService->findById($id);

        $this->taskService->delete($task);

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }
}