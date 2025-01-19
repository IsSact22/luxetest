<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                ...$this->isPrecognitive() ? ['unique:'.User::class] : ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'locale' => ['required', new Enum(\App\LocaleEnum::class)],
        ];
    }
}
