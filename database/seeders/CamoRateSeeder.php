<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CamoRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $camoRates = [
            [
                'engine_type_id' => 1,
                'code' => 'I05',
                'name' => 'HORAS HOMBRE TALLER - TURBO',
                'mount' => 80,
            ],
            [
                'engine_type_id' => 2,
                'code' => 'I06',
                'name' => 'HORAS HOMBRE TALLER - PISTON',
                'mount' => 60,
            ],
            [
                'engine_type_id' => 3,
                'code' => 'I07',
                'name' => 'HORAS HOMBRE TALLER - TURBINAS',
                'mount' => 90,
            ],
        ];
        foreach ($camoRates as $camoRate) {
            \App\Models\CamoRate::query()->create($camoRate);
        }
    }
}
