<?php

namespace App\Modules\Contacts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendContactEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'bodyText' => ['required', 'string'],
        ];
    }
}