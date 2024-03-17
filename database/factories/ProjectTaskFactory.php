<?php

namespace Database\Factories;

use App\Models\ProjectService;
use App\Models\ProjectTask;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<ProjectTask>
 */
class ProjectTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $endDate = date('Y-m-d', strtotime('+3 months'));

        return [
            'project_service_id' => ProjectService::inRandomOrder()->first()->id,
            'name' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['backlog', 'ready', 'in_progress', 'in_review', 'done']),
            'due_date' => $this->faker->dateTimeBetween(now(), $endDate)->format('Y-m-d'),
        ];
    }
}
