<?php

namespace App\Modules\Settings\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
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
