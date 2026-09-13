<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240',
            ],
        ];
    }
}