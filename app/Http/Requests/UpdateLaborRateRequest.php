<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaborRateRequest extends FormRequest
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
            'rateable_id' => ['required', 'integer'],
            'rateable_type' => ['required', 'string', Rule::in(['App\\Models\\AdminRate', 'App\\Models\\EngineType'])],
            'code' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('labor_rates', 'code')] :
                    [
                        'required',
                        'string',
                        Rule::unique('labor_rates', 'code')->ignore($this->labor_rate),
                    ],
            ],
            'name' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('labor_rates', 'name')] :
                    [
                        'required',
                        'string',
                        Rule::unique('labor_rates', 'name')->ignore($this->labor_rate),
                    ],
            ],
            'amount' => ['required', 'numeric'],
        ];
    }
}
