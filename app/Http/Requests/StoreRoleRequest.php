<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
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
                    [Rule::unique('roles', 'name')] :
                    ['required', 'string', Rule::unique('roles', 'name')],
            ],
            'guard_name' => ['required', 'string'],
            'permissions' => ['required'],
        ];
    }
}
