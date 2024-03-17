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
    protected $model = Aircraft::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'registration' => $this->faker->bothify('YV-####-#'),
            'name' => fake()->unique()->randomElement(['F900EX-097', 'G650ER', 'CJ4', 'LJ45', 'P180', 'E55P', 'C750', 'H800XP']),
        ];
    }
}
