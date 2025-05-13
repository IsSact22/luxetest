<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir registro público
    }

    /**
     * @OA\Schema(
     *     schema="RegisterRequest",
     *     required={"name", "email", "password", "password_confirmation"},
     *     @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="user@example.com"),
     *     @OA\Property(property="password", type="string", minLength=8, example="SecurePassword123!"),
     *     @OA\Property(property="password_confirmation", type="string", example="SecurePassword123!")
     * )
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.uncompromised' => 'La contraseña ha aparecido en filtraciones de datos. Por favor elija otra.',
        ];
    }
}