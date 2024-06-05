<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCamoActivityRateRequest extends FormRequest
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
                    [Rule::unique('camo_activity_rates', 'code')->ignore($this->camo_activity_rate)] :
                    ['required', Rule::unique('camo_activity_rates', 'code')->ignore($this->camo_activity_rate)],
            ],
            'name' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camo_activity_rates', 'name')->ignore($this->camo_activity_rate)] :
                    ['required', Rule::unique('camo_activity_rates', 'name')->ignore($this->camo_activity_rate)],
            ],
            'mount' => ['numeric', 'nullable'],
        ];
    }
}
