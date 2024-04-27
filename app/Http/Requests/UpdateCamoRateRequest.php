<?php

namespace App\Http\Requests;

use App\Models\CamoRate;
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
                ...$this->isPrecognitive() ? [Rule::unique(CamoRate::class)->ignore($this->id)] : ['required', 'string', Rule::unique(CamoRate::class)->ignore($this->id)],
            ],
            'name' => [
                ...$this->isPrecognitive() ? [Rule::unique(CamoRate::class)->ignore($this->id)] : ['required', 'string', Rule::unique(CamoRate::class)->ignore($this->id)],
            ],
            'mount' => ['required', 'numeric'],
        ];
    }
}
