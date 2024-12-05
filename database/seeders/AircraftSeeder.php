<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use Illuminate\Database\Seeder;

class AircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aircraft::create([
            'model_aircraft_id' => 1,
            'register' => 'YV-1234',
            'serial' => '650-1234',
        ]);
    }
}
