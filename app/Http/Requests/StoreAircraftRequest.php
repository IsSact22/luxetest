<?php

namespace App\Http\Requests;

use App\Models\Aircraft;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAircraftRequest extends FormRequest
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
        $modelExists = Aircraft::onlyTrashed()
            ->where('model_aircraft_id', $this->model_aircraft_id)
            ->exists();

        $registerExists = Aircraft::onlyTrashed()
            ->where('register', $this->register)
            ->exists();

        $serialExists = Aircraft::onlyTrashed()
            ->where('serial', $this->serial)
            ->exists();

        return [
            'model_aircraft_id' => [
                ...$modelExists ? [] : [
                    ...$this->isPrecognitive() ?
                        [Rule::unique('model_aircrafts', 'id')] :
                        ['required', 'integer', 'exists:model_aircrafts,id']
                ]
            ],
            'register' => [
                ...$registerExists ? [] : [
                    ...$this->isPrecognitive() ?
                        [Rule::unique('aircrafts', 'register')] :
                        ['required', 'unique:aircrafts,register'],
                ],
            ],
            'serial' => [
                ...$serialExists ? [] : [
                    ...$this->isPrecognitive() ?
                        [Rule::unique('aircrafts', 'serial')] :
                        ['required', Rule::unique('aircrafts', 'serial')],
                ],
            ],
        ];
    }
}
