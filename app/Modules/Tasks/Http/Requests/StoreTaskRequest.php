<?php

namespace App\Modules\Tasks\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'due_date' => ['required', 'date'],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'status' => [
                'sometimes',
                'in:pending,in_progress,completed,cancelled',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'taskable_type' => [
                'nullable',
                'string',
                'in:App\\Modules\\Leads\\Models\\Lead,App\\Modules\\Deals\\Models\\Deal,App\\Modules\\Tickets\\Models\\Ticket',
            ],

            'taskable_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}