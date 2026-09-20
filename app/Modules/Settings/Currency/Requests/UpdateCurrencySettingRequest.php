<?php

namespace App\Modules\Settings\Currency\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrencySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => [
                'required',
                'string',
                Rule::in(['IRT', 'IRR', 'USD']),
            ],

            'usd_rate' => [
                'nullable',
                'numeric',
                'gt:0',
            ],
        ];
    }
}