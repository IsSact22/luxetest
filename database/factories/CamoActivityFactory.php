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
        $awrText = 'V3/Airframe inspections';
        $awr = sprintf('%08d-%s', $awrNumber, $awrText);
        $required = fake()->boolean;

        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $approvalStatus = $required ? 'approved' : fake()->randomElement(['approved', 'pending']);

        return [
            'camo_id' => $camo->id,
            'required' => $required,
            'date' => $status === 'pending' ? null : fake()->dateTimeBetween($camo->created_at, '+2 day'),
            'name' => fake()->word,
            'description' => fake()->paragraph,
            'status' => $approvalStatus === 'pending' ? $approvalStatus : $status,
            'comments' => fake()->paragraph,
            'labor_mount' => fake()->randomFloat(2, 0, 10000),
            'material_mount' => fake()->randomFloat(2, 0, 10000),
            'material_information' => fake()->sentence,
            'awr' => $awr,
            'approval_status' => $approvalStatus,
        ];
    }
}
