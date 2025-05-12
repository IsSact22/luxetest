<?php

namespace Database\Seeders;

use App\Models\AdminRate;
use Illuminate\Database\Seeder;

class AdminRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRates = [
            [
                'id' => 1,
                'name' => 'Tarifa A',
                'description' => 'Tarifa Administrativa A',
            ],
            [
                'id' => 2,
                'name' => 'Tarifa B',
                'description' => 'Tarifa Administrativa B',
            ],
            [
                'id' => 3,
                'name' => 'Tarifa C',
                'description' => 'Tarifa Administrativa C',
            ],
            [
                'id' => 4,
                'name' => 'Tarifa D',
                'description' => 'Tarifa Administrativa D',
            ],
        ];
        foreach ($adminRates as $adminRate) {
            AdminRate::query()->create($adminRate);
        }
    }
}
