<?php

namespace App\Modules\Deals\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'contact_id' => ['nullable', 'integer', 'exists:contacts,id'],
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'stage_id' => ['required', 'integer', 'exists:deal_stages,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'probability' => ['required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'status' => ['required', 'in:open,won,lost'],
            'loss_reason' => ['nullable', 'string'],
        ];
    }
}