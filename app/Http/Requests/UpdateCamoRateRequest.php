<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCamoRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return request()->user()->can('backoffice-update');
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
            'engine_type_id' => ['required', 'integer', 'exists:engine_types,id'],
            'code' => [
                'required',
                'string',
                Rule::unique('camo_rates', 'code')->ignore($this->camo_rate),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('camo_rates', 'name')->ignore($this->camo_rate),
            ],
            'mount' => ['required', 'numeric'],
        ];
    }
}
