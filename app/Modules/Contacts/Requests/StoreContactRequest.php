<?php

namespace App\Modules\Contacts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'name' => ['required', 'string', 'max:255'],
            'customer_category' => ['nullable', 'in:VIP,normal,potential'],
            'address' => ['nullable', 'string'],

            'phones' => ['nullable', 'array'],
            'phones.*' => ['string', 'max:50'],

            'emails' => ['nullable', 'array'],
            'emails.*' => ['email', 'max:255'],
        ];
    }
}