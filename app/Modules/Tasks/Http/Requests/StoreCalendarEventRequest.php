<?php

namespace App\Modules\Tasks\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                'in:meeting,call,reminder',
            ],

            'start_at' => [
                'required',
                'date',
            ],

            'end_at' => [
                'nullable',
                'date',
                'after_or_equal:start_at',
            ],

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }
}