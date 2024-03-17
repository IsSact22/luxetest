<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Override;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $hours = fake()->numberBetween(1, 24);
        $minutes = fake()->numberBetween(1, 60);
        $roundedMinutes = round($minutes / 10) * 10;
        $estimateTime = Carbon::createFromTime($hours, $roundedMinutes);

        return [
            'name' => fake()->sentence(2),
            'description' => fake()->text,
            'estimate_time' => $estimateTime->format('H:i'),
            'has_material' => fake()->boolean,
        ];
    }
}
