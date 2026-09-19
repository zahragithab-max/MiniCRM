<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRuleRecipientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', 'max:100'],
            'value' => ['sometimes', 'string', 'max:255'],
        ];
    }
}