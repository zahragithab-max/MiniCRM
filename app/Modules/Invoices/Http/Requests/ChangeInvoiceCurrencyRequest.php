<?php

namespace App\Modules\Invoices\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeInvoiceCurrencyRequest extends FormRequest
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
        ];
    }
}