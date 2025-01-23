<?php

namespace Database\Factories;

use App\Models\Aircraft;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Aircraft>
 */
class AircraftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $modelAircraft = \App\Models\ModelAircraft::query()->inRandomOrder()->first();

        return [
            'model_aircraft_id' => $modelAircraft->id,
            'register' => 'YV'.fake()->regexify('\d{3,4}'),
            'serial' => fake()->bothify('???####-?#####'),
        ];
    }
}
