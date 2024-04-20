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
        $brands = [
            'Cessna',
            'Bombardier',
            'Gulfstream',
            'Embraer',
            'Dassault',
            'Hawker Beechcraft',
            'Learjet',
            'Piaggio',
            'Pilatus',
            'Emivest Aerospace',
            'Nextant Aerospace',
            'Honda Aircraft Company',
        ];

        foreach ($brands as $brand) {
            BrandAircraft::create(['name' => $brand]);
        }
    }
}
