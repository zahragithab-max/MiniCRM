<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRuleRecipientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }
}