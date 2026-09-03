<?php

namespace App\Modules\Accounts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'customer_category' => ['sometimes', 'nullable', 'in:VIP,normal,potential'],
        ];
    }
}