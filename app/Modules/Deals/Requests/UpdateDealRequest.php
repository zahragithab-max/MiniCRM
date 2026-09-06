<?php

namespace App\Modules\Deals\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'account_id' => ['sometimes', 'required', 'integer', 'exists:accounts,id'],
            'contact_id' => ['sometimes', 'nullable', 'integer', 'exists:contacts,id'],
            'owner_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'stage_id' => ['sometimes', 'required', 'integer', 'exists:deal_stages,id'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'probability' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'required', 'in:open,won,lost'],
            'loss_reason' => ['sometimes', 'nullable', 'string'],
        ];
    }
}