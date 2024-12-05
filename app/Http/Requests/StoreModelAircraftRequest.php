<?php

namespace App\Http\Requests;

use App\Models\ModelAircraft;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Verifica si existe un modelo borrado lógicamente con el mismo nombre
        $exists = ModelAircraft::onlyTrashed()
            ->where('name', $this->name)
            ->where('brand_aircraft_id', $this->brand_aircraft_id)
            ->where('engine_type_id', $this->brand_aircraft_id)
            ->exists();

        return [
            'brand_aircraft_id' => ['required'],
            'engine_type_id' => ['required'],
            'name' => [
                ...$exists ? [] : [
                    ...$this->isPrecognitive() ?
                        ['unique:model_aircrafts,name'] :
                        ['required', 'min:3', 'max:191', 'unique:model_aircrafts,name'],
                ],
            ],
        ];
    }
}
