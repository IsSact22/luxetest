<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAircraftRequest extends FormRequest
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
            'model_aircraft_id' => ['required', 'integer', 'exists:model_aircrafts,id'],
            'register' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('aircrafts', 'register')->ignore($this->aircraft)] :
                    ['required', Rule::unique('aircrafts', 'register')->ignore($this->aircraft)],
            ],
            'serial' => ['required', Rule::unique('aircrafts', 'serial')->ignore($this->aircraft)],
        ];
    }
}
