<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCamoActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can('create-activity');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'camo_id' => ['required', 'integer', 'exists:camos,id'],
            'labor_rate_id' => ['required', 'exists:labor_rates,id'],
            'labor_rate_value_id' => ['required', 'exists:labor_rate_values,id'],
            'required' => ['sometimes', 'boolean'],
            'date' => ['nullable', 'date'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'estimate_time' => ['required', 'numeric'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after:started_at'],
            'status' => ['required', 'string'],
            'comments' => ['nullable', 'string'],
            'labor_mount' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'material_mount' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'material_information' => ['nullable', 'string'],
            'awr' => ['nullable', 'string'],
            'approval_status' => ['nullable', 'string'],
            'priority' => ['required', 'integer', 'min:1', 'max:3'],
        ];
    }
}
