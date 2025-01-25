<?php

namespace Database\Seeders;

use App\Models\ModelAircraft;
use Illuminate\Database\Seeder;

class ModelAircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelAircraft::query()->create([
            'name' => '650',
            'brand_aircraft_id' => 1,
            'engine_type_id' => 3,
        ]);
    }
}
