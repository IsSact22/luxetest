<?php

namespace Database\Seeders;

use App\Models\CamoActivity;
use Illuminate\Database\Seeder;

class CamoActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomNumber = random_int(7, 150);
        CamoActivity::factory($randomNumber)->create();
    }
}
