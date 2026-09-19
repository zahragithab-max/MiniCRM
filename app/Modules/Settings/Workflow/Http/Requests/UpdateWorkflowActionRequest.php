<?php

namespace App\Modules\Settings\Workflow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkflowActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', 'max:100'],
            'channel' => ['sometimes', 'string', 'max:100'],
            'recipient' => ['sometimes', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}