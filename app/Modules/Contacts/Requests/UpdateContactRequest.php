<?php

namespace App\Modules\Contacts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['sometimes', 'required', 'integer', 'exists:accounts,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'customer_category' => ['sometimes', 'nullable', 'in:VIP,normal,potential'],
            'address' => ['sometimes', 'nullable', 'string'],

            'phones' => ['sometimes', 'nullable', 'array'],
            'phones.*' => ['string', 'max:50'],

            'emails' => ['sometimes', 'nullable', 'array'],
            'emails.*' => ['email', 'max:255'],
        ];
    }
}