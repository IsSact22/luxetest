<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAircraftRequest extends FormRequest
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
            'model_aircraft_id' => ['required', 'integer', 'exists:aircrafts,id'],
            'register' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('aircrafts', 'register')] :
                    ['required', 'unique:aircrafts,register'],
            ],
            'serial' => ['required', Rule::unique('aircrafts', 'serial')],
        ];
    }
}
