<?php

namespace App\Modules\Settings\Currency\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvertCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'gte:0',
            ],

            'from' => [
                'required',
                'string',
                Rule::in(['IRT', 'IRR', 'USD']),
            ],

            'to' => [
                'required',
                'string',
                Rule::in(['IRT', 'IRR', 'USD']),
            ],
        ];
    }
}