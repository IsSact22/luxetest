<?php

namespace App\Http\Requests;

use App\Models\BrandAircraft;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandAircraftRequest extends FormRequest
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
        $exists = BrandAircraft::onlyTrashed()->where('name', $this->name)->exists();

        return [
            'name' => [
                // Si no existe un registro borrado con ese nombre, aplica la regla unique
                ...$exists ? [] : [
                    ...$this->isPrecognitive() ?
                        ['unique:brand_aircrafts,name'] :
                        ['min:3', 'max:191', 'unique:brand_aircrafts,name'],
                ],
            ],
        ];
    }
}
