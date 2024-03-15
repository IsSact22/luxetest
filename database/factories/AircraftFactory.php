<?php

namespace Database\Factories;

use App\Models\Aircraft;
use App\Models\AircraftModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aircraft>
 */
class AircraftFactory extends Factory
{
    protected $model = Aircraft::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => fake()->randomElement([8, 9, 10, 11, 12, 13, 14]),
            'aircraft_model_id' => AircraftModel::all()->random()->id,
            'name' => fake()->sentence(3),
            'construction_date' => $this->faker->dateTimeBetween('-10 years', now()),
            'serial' => $this->faker->unique()->bothify('??####?##???###'),
            'registration' => $this->faker->bothify('YV-######'),
            'flight_hours' => $this->faker->numberBetween(0, 3000),
        ];
    }
}
