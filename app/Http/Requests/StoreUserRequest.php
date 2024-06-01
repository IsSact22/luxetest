<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'role' => ['required'],
            'owner_id' => ['sometimes', 'exists:users,id'],
            'name' => ['required'],
            'email' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('users', 'email')] :
                    ['required', 'email', Rule::unique('users', 'email')],
            ],
            'password' => ['required'],
            'password_confirmation' => [
                ...$this->isPrecognitive() ?
                    ['same:password'] :
                    ['required', 'same:password'],
            ],
            'avatar' => ['nullable', 'image'],
        ];
    }
}
