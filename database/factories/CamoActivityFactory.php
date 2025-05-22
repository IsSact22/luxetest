<?php

namespace Database\Factories;

use App\Models\Camo;
use App\Models\CamoActivity;
use App\Models\LaborRate;
use DateInterval;
use DateTime;
use Exception;
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
     *
     * @throws Exception
     */
    #[Override]
    public function definition(): array
    {
        $camo = Camo::query()->inRandomOrder()->first();
        $awrNumber = fake()->randomNumber(8);
        $awrText = fake()->bothify('V#/Airframe inspections');
        $awr = sprintf('%08d-%s', $awrNumber, $awrText);
        $required = fake()->boolean;

        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $approvalStatus = $required ? 'approved' : fake()->randomElement(['approved', 'pending']);
        $hasDate = $status === 'pending' || $approvalStatus === 'pending';
        $laborRate = LaborRate::query()->inRandomOrder()->first();
        $estimateTime = fake()->randomFloat(2, 2, 240);
        $startedAt = $hasDate ? null : fake()->dateTimeBetween($camo->created_at, '+1 day');

        if (! is_null($startedAt)) {
            $completedAt = (new DateTime($startedAt))
                ->add(new DateInterval('PT'.$estimateTime.'H'))
                ->format('Y-m-d H:i:s');
        } else {
            $completedAt = null;
        }

        if (! is_null($startedAt) && ! is_null($completedAt)) {
            $startedAt = strtotime($startedAt);
            $completedAt = strtotime($completedAt);
            // calcular la direfencia en segundo
            $secondsDiff = $completedAt - $startedAt;
            $hoursDiff = $secondsDiff / 3600;
            // Calcular horas laborales
            $hoursWorked = min($hoursDiff, 8);
            $rate = $laborRate->mount;
            $laborMount = $hoursWorked * $rate;
        } else {
            $laborMount = $estimateTime * $laborRate->mount;
        }

        return [
            'camo_id' => $camo->id,
            'labor_rate_id' => $laborRate->id,
            'required' => $required,
            'date' => $hasDate ? null : fake()->dateTimeBetween($camo->created_at, '+2 day'),
            'name' => fake()->unique()->word,
            'description' => fake()->paragraph(2, false),
            'estimate_time' => $estimateTime,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'status' => $approvalStatus === 'pending' ? $approvalStatus : $status,
            'comments' => fake()->paragraph(2, false),
            'labor_mount' => $laborMount, // fake()->randomFloat(2, 90, 10000),
            'material_mount' => 0.0,
            'material_information' => fake()->paragraph(2, false),
            'awr' => $awr,
            'approval_status' => $approvalStatus,
            'priority' => fake()->randomElement([1, 2, 3]),
        ];
    }
}
