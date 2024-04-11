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
        Camo::factory(2)->create();
        $randomNumber = random_int(25, 52);
        CamoActivity::factory($randomNumber)->create();

        Camo::factory()->create([
            'owner_id' => 13,
            'cam_id' => 12,
        ]);
        $randomNumber = random_int(25, 52);
        CamoActivity::factory($randomNumber)->create();
    }
}
