<?php

namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}