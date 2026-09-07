<?php

namespace App\Modules\Tasks\Services;

use App\Modules\Settings\Auth\Models\User;
use App\Modules\Tasks\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Task::with(['assignee', 'taskable'])
            ->latest('due_date')
            ->paginate($perPage);
    }

    public function findById(int $id): Task
    {
        /** @var Task $task */
        $task = Task::with(['assignee', 'taskable'])
            ->findOrFail($id);

        return $task;
    }

    public function create(array $data, User $user): Task
    {
        if (!array_key_exists('assigned_to', $data)) {
            $data['assigned_to'] = $user->id;
        }

        return Task::create($data)
            ->load(['assignee', 'taskable']);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh(['assignee', 'taskable']);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}