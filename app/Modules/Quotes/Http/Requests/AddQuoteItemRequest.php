<?php

namespace App\Modules\Quotes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddQuoteItemRequest extends FormRequest
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

            'discount' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }
}