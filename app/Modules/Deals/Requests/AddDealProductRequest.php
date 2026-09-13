<?php

namespace App\Modules\Deals\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddDealProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'unit_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'discount_type' => [
                'sometimes',
                Rule::in(['fixed', 'percentage']),
            ],
            'discount' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }
}