<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCamoRateRequest extends FormRequest
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
            'engine_type_id' => ['required', 'integer'],
            'code' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camo_rates', 'code')] :
                    ['required', 'string', Rule::unique('camo_rates', 'code')],
            ],
            'name' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camo_rates', 'name')] :
                    ['required', 'string', Rule::unique('camo_rates', 'name')],
            ],
            'mount' => ['required', 'numeric'],
        ];
    }
}
