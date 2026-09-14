<?php

namespace App\Modules\Settings\System\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVatRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vat_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }
}