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
        $owner = User::role('owner')->where('owner_id', null)->inRandomOrder()->first();
        $cam = User::role('cam')->where('owner_id', null)->inRandomOrder()->first();
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+8 months');

        return [
            'customer' => fake()->taxpayerIdentificationNumber(),
            'owner_id' => $owner->id,
            'contract' => fake()->bothify('000#####-V#'),
            'cam_id' => $cam->id,
            'aircraft' => strtoupper(fake()->bothify('?###??-###')),
            'description' => fake()->words(7, true),
            'start_date' => $startDate,
            'finish_date' => $endDate,
            'location' => 'OMZ',
        ];
    }
}
