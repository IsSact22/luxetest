<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'role' => 'sometimes',
            'owner_id' => 'sometimes|exists:users',
            'name' => 'sometimes',
            'email' => 'sometimes|email|unique:users,id,email,'.$this->id,
            'password' => 'sometimes',
            'confirmation_password' => 'sometimes|same:password',
            'avatar' => 'sometimes|image',
        ];
    }
}
