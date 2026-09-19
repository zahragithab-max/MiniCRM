<?php

namespace App\Modules\Settings\Workflow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkflowActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:100'],
            'channel' => ['required', 'string', 'max:100'],
            'recipient' => ['required', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}