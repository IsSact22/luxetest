<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandAircraftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                ...$this->isPrecognitive() ?
                    ['min:3', 'max:191', 'unique:brand_aircrafts,name'] :
                    ['min:3', 'max:191', 'brand_aircrafts,name'],
            ],
        ];
    }
}
