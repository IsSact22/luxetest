<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModelAircraftRequest extends FormRequest
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
            'brand_aircraft_id' => ['required'],
            'engine_type_id' => ['required'],
            'name' => [
                'required',
                'min:3',
                'max:191',
                Rule::unique('model_aircrafts', 'name')->ignore($this->model_aircraft),
            ],
        ];
    }
}
