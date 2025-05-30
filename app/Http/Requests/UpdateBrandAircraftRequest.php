<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandAircraftRequest extends FormRequest
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
                    ['min:3', 'max:191', Rule::unique('brand_aircrafts', 'name')->ignore($this->engine_type)] :
                    ['min:3', 'max:191', Rule::unique('brand_aircrafts', 'name')->ignore($this->engine_type)],
            ],
        ];
    }
}
