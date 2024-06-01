<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreModelAircraftRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_aircraft_id' => ['required'],
            'engine_aircraft_id' => ['required'],
            'name' => [
                ...$this->isPrecognitive() ?
                    ['unique:model_aircrafts,name'] :
                    ['required', 'min:3', 'max:191', 'unique:model_aircrafts,name'],

            ],
        ];
    }
}
