<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('roles', 'name')->ignore($this->role)] :
                    ['required', 'string', Rule::unique('roles', 'name')->ignore($this->role)],
            ],
            'guard_name' => ['nullable', 'string'],
            'permissions' => ['required'],
        ];
    }
}
