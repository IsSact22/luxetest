<?php

namespace Database\Factories;

use App\Models\Aircraft;
use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $aircraft = Aircraft::factory()->create();

        return [
            'client_id' => Client::all()->random()->id,
            'aircraft_id' => $aircraft->id,
            'date' => fake()->dateTimeBetween('-15 days', 'now')->format('Y-m-d H:i:s'),
            'name' => $aircraft->name,
        ];
    }

    #[Override]
    public function configure(): Factory|ProjectFactory
    {
        return $this->afterCreating(function (Project $project) {
            $numberOfServices = random_int(7, 52);
            $serviceIds = Service::inRandomOrder()->limit($numberOfServices)->pluck('id');
            $project->services()->attach($serviceIds);
        });
    }
}
