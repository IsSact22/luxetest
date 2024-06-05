<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCamoActivityRateRequest extends FormRequest
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
            'code' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camo_activity_rates', 'code')] :
                    ['required', Rule::unique('camo_activity_rates', 'code')],
            ],
            'name' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camo_activity_rates', 'name')] :
                    ['required', Rule::unique('camo_activity_rates', 'name')],
            ],
            'mount' => ['numeric', 'nullable'],
        ];
    }
}
