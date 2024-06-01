<?php

namespace Database\Seeders;

use App\Models\AdminRate;
use App\Models\EngineType;
use App\Models\LaborRate;
use Illuminate\Database\Seeder;

class LaborRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laborRates = [
            [
                'rateable_id' => 2,
                'rateable_type' => EngineType::class,
                'code' => 'I05',
                'name' => 'Man Hours Workshop TURBO-FAN',
                'mount' => 80,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => EngineType::class,
                'code' => 'I06',
                'name' => 'Man Hours Workshop - PISTON',
                'mount' => 60,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => EngineType::class,
                'code' => 'I07',
                'name' => 'Man Hours Workshop - TURBINES',
                'mount' => 90,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I08',
                'name' => 'Man Hours Workshop Avionics',
                'mount' => 60,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I09',
                'name' => 'Man Hours Workshop Electrical',
                'mount' => 50,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I10',
                'name' => 'Man Hours Workshop Laminate',
                'mount' => 45,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I11',
                'name' => 'Man Hours Workshop Painting',
                'mount' => 50,
            ],
            [
                'rateable_id' => 2,
                'rateable_type' => AdminRate::class,
                'code' => 'I20',
                'name' => 'Sales of Parts',
                'mount' => 0,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => AdminRate::class,
                'code' => 'I25',
                'name' => 'Equipment Rental & Technical Service',
                'mount' => 0,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I30',
                'name' => 'Serv. Engineering & Planning',
                'mount' => 0,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I35',
                'name' => 'CAM',
                'mount' => 0,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => AdminRate::class,
                'code' => 'I40',
                'name' => 'Space Rental',
                'mount' => 0,
            ],
            [
                'rateable_id' => 4,
                'rateable_type' => AdminRate::class,
                'code' => 'I90',
                'name' => 'Administrative Management',
                'mount' => 0,
            ],
        ];
        foreach ($laborRates as $camoRate) {
            LaborRate::query()->create($camoRate);
        }
    }
}
