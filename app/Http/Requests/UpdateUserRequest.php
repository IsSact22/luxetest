<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'role' => ['sometimes'],
            'owner_id' => ['sometimes', 'exists:users'],
            'name' => ['sometimes'],
            'email' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('users', 'email')->ignore($this->user)] :
                    ['sometimes', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            ],
            'password' => ['sometimes'],
            'password_confirmation' => [
                // Cuando password está presente, password_confirmation debe estar presente y ser igual a password
                'present_if:password,!=,', // Verifica si password está presente y no vacío
                'same:password',
            ],
            'avatar' => ['nullable', 'image'],
        ];
    }
}
