<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCamoActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can('update-activity');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'camo_id' => ['required'],
            'required' => ['sometimes', 'boolean'],
            'date' => ['nullable', 'date'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', 'string'],
            'comments' => ['required', 'string'],
            'labor_mount' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'material_mount' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'material_information' => ['required', 'string'],
            'awr' => ['nullable', 'string'],
            'approval_status' => ['nullable', 'string'],
        ];
    }
}
