<?php

namespace Database\Factories;

use App\Models\Camo;
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
        $camo = Camo::inRandomOrder()->first();
        $awrNumber = $this->faker->randomNumber(8);
        $awrText = fake()->bothify('V#/Airframe inspections');
        $awr = sprintf('%08d-%s', $awrNumber, $awrText);
        $required = fake()->boolean;

        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $approvalStatus = $required ? 'approved' : fake()->randomElement(['approved', 'pending']);
        $hasDate = $status === 'pending' || $approvalStatus === 'pending';

        return [
            'camo_id' => $camo->id,
            'required' => $required,
            'date' => $hasDate ? null : fake()->dateTimeBetween($camo->created_at, '+2 day'),
            'name' => fake()->word,
            'description' => fake()->paragraph(2, false),
            'status' => $approvalStatus === 'pending' ? $approvalStatus : $status,
            'comments' => fake()->paragraph(2, false),
            'labor_mount' => fake()->randomFloat(2, 90, 10000),
            'material_mount' => fake()->randomFloat(2, 100, 10000),
            'material_information' => fake()->paragraph(2, false),
            'awr' => $awr,
            'approval_status' => $approvalStatus,
        ];
    }
}
