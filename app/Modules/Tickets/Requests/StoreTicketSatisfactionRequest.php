<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketSatisfactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
        ];
    }
}