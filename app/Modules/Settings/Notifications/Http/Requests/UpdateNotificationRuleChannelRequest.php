<?php

namespace App\Modules\Settings\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRuleChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channel' => ['sometimes', 'string', 'max:100'],
            'is_enabled' => ['sometimes', 'boolean'],
        ];
    }
}