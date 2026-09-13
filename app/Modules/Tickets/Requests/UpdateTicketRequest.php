<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['sometimes', 'string', 'max:100'],
            'status' => [
                'sometimes',
                'in:open,pending,resolved,closed',
            ],
            'priority' => [
                'sometimes',
                'in:low,medium,high',
            ],
            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'sla_hours' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }
}