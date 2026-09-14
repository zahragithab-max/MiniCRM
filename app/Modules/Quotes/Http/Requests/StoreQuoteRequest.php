<?php

namespace App\Modules\Quotes\Http\Requests;

use App\Support\Helpers\JalaliHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->filled('issue_date')) {
            $data['issue_date'] = JalaliHelper::toGregorian(
                $this->input('issue_date')
            );
        }

        if ($this->filled('valid_until')) {
            $data['valid_until'] = JalaliHelper::toGregorian(
                $this->input('valid_until')
            );
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'deal_id' => ['required', 'integer', 'exists:deals,id'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'contact_id' => ['nullable', 'integer', 'exists:contacts,id'],
            'issue_date' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:issue_date'],
            'status' => [
                'sometimes',
                Rule::in([
                    'draft',
                    'sent',
                    'accepted',
                    'rejected',
                    'expired',
                ]),
            ],
            'vat_enabled' => ['sometimes', 'boolean'],
        ];
    }
}

