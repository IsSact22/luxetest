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
                    [Rule::unique('users', 'email')->ignore($this->id)] :
                    ['required', 'email', Rule::unique('users', 'email')->ignore($this->id)],
            ],
            'password' => ['sometimes'],
            'password_confirmation' => [
                ...$this->isPrecognitive() ?
                    ['present_if:password', 'same:password'] :
                    ['present_if:password', 'same:password'],
            ],
            'avatar' => ['nullable', 'image'],
        ];
    }
}
