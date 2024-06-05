<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'customer' => ['required'],
            'owner_id' => ['required', 'exists:users,id'],
            'contract' => [
                ...$this->isPrecognitive() ?
                    [Rule::unique('camos', 'contract')] :
                    ['required', Rule::unique('camos', 'contract')],
            ],
            'cam_id' => ['required', 'exists:users,id'],
            'aircraft_id' => ['required'],
            'description' => ['nullable', 'string', 'max:191'],
            'start_date' => ['required', 'date', 'after_or_equal:today-1 month'],
            'estimate_finish_date' => ['required', 'date', 'after_or_equal:start_date'],
            'finish_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['required'],
        ];
    }
}
