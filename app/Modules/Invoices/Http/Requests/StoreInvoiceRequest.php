<?php

namespace App\Modules\Invoices\Http\Requests;

use App\Support\Helpers\JalaliHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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

        if ($this->filled('due_date')) {
            $data['due_date'] = JalaliHelper::toGregorian(
                $this->input('due_date')
            );
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'deal_id' => [
                'required',
                'integer',
                'exists:deals,id',
            ],

            'account_id' => [
                'required',
                'integer',
                'exists:accounts,id',
            ],

            'contact_id' => [
                'nullable',
                'integer',
                'exists:contacts,id',
            ],

            'quote_id' => [
                'nullable',
                'integer',
                'exists:quotes,id',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:issue_date',
            ],

            'vat_enabled' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}