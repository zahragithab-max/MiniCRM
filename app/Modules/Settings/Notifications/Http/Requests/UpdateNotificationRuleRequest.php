<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workflow_id' => ['sometimes', 'nullable', 'integer', 'exists:workflows,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'event' => ['sometimes', 'string', 'max:255'],
            'message' => ['sometimes', 'string'],
            'is_enabled' => ['sometimes', 'boolean'],
        ];
    }
}