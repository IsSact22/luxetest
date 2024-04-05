<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCamoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can('create-camo');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer' => 'required|unique:camos,customer',
            'owner_id' => 'required|exists:users,id',
            'contract' => 'required|unique:camos,contract',
            'cam_id' => 'required|exists:users,id',
            'aircraft' => 'required|unique:camos,aircraft',
            'description' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'finish_date' => 'required|date|after:today',
            'location' => 'required',
            'activities' => 'required|array|min:1',
        ];
    }
}
