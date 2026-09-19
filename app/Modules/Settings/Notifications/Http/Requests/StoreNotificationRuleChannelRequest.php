<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRuleChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channel' => ['required', 'string', 'max:100'],
            'is_enabled' => ['sometimes', 'boolean'],
        ];
    }
}