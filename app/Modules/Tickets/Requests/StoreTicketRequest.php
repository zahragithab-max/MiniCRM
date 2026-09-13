<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'status' => [
                'sometimes',
                'in:open,pending,resolved,closed',
            ],
            'priority' => [
                'required',
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
