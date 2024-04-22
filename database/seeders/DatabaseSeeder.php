<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            UserSeeder::class,
            BrandAircraftSeeder::class,
            EngineTypeSeeder::class,
            CamoRateSeeder::class,
            BrandAircraftSeeder::class,
            ModelAircraftSeeder::class,
            AircraftSeeder::class,
            CamoActivityRateSeeder::class,
            CamoSeeder::class,
        ]);
    }
}
