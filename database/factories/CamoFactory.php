<?php

namespace Database\Factories;

use App\Models\Camo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Camo>
 */
class CamoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $owner = User::role('owner')->inRandomOrder()->first();
        $cam = User::role('cam')->inRandomOrder()->first();
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+8 months');

        $usedAircraftIds = \App\Models\Camo::query()->pluck('aircraft_id')->toArray();
        $availableAircraftIds = \App\Models\Aircraft::query()->whereNotIn('id', $usedAircraftIds)->pluck('id');

        return [
            'customer' => fake()->unique()->taxpayerIdentificationNumber(),
            'owner_id' => $owner->id,
            'contract' => fake()->unique()->bothify('000#####-V#'),
            'cam_id' => $cam->id,
            'aircraft_id' => fake()->unique()->randomElement($availableAircraftIds),
            'description' => fake()->unique()->words(7, true),
            'start_date' => $startDate,
            'finish_date' => $endDate,
            'location' => 'OMZ',
        ];
    }
}
