<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workflow_id' => ['nullable', 'integer', 'exists:workflows,id'],
            'name' => ['required', 'string', 'max:255'],
            'event' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'is_enabled' => ['sometimes', 'boolean'],
        ];
    }
}