<?php

namespace Database\Seeders;

use App\Models\BrandAircraft;
use Illuminate\Database\Seeder;

class BrandAircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BrandAircraft::query()->create(['name' => 'CESSNA AIRCRAFT COMPANY']);
    }
}
