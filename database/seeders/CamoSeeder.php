<?php

namespace Database\Seeders;

use App\Models\Camo;
use App\Models\CamoActivity;
use Illuminate\Database\Seeder;

class CamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Camo::factory()->create();
        $randomNumber = random_int(25, 150);
        CamoActivity::factory($randomNumber)->create();
    }
}
