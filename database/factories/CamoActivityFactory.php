<?php

namespace Database\Factories;

use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<CamoActivity>
 */
class CamoActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $camo = \App\Models\Camo::query()->inRandomOrder()->first();
        $awrNumber = fake()->randomNumber(8);
        $awrText = fake()->bothify('V#/Airframe inspections');
        $awr = sprintf('%08d-%s', $awrNumber, $awrText);
        $required = fake()->boolean;

        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $approvalStatus = $required ? 'approved' : fake()->randomElement(['approved', 'pending']);
        $hasDate = $status === 'pending' || $approvalStatus === 'pending';

        return [
            'camo_id' => $camo->id,
            'camo_activity_rate_id' => \App\Models\CamoActivityRate::query()->inRandomOrder()->first()->id,
            'required' => $required,
            'date' => $hasDate ? null : fake()->dateTimeBetween($camo->created_at, '+2 day'),
            'name' => fake()->unique()->word,
            'description' => fake()->unique()->paragraph(2, false),
            'status' => $approvalStatus === 'pending' ? $approvalStatus : $status,
            'comments' => fake()->unique()->paragraph(2, false),
            'labor_mount' => fake()->randomFloat(2, 90, 10000),
            'material_mount' => fake()->randomFloat(2, 100, 10000),
            'material_information' => fake()->unique()->paragraph(2, false),
            'awr' => $awr,
            'approval_status' => $approvalStatus,
        ];
    }
}
