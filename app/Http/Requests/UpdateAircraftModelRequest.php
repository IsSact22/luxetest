<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAircraftModelRequest extends FormRequest
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
            'manufacturer_id' => 'required|integer|exists:manufacturers',
            'name' => 'required|string|unique:aircraft_models,name,'.$this->id,
            'category' => 'required|string',
            'class' => 'required|string',
            'motor_type' => 'required|string',
            'motor_qty' => 'required|integer',
            'passenger_qty' => 'required',
        ];
    }
}
