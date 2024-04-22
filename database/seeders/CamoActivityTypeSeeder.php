<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CamoActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $camoActivityTypes = [
            [
                'camo_activity_rate_id' => 1,
                'name' => 'AVIONICA',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 2,
                'name' => 'ELECTRICA',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 3,
                'name' => 'LAMINADO',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 4,
                'name' => 'PINTURA',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 5,
                'name' => 'VENTA/PARTES',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 6,
                'name' => 'ALQUILER EQUIP SERV. TEC.',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 7,
                'name' => 'ING/PLANIFICACION',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 8,
                'name' => 'CAMP',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 9,
                'name' => 'ALQUILER ESPACIO',
                'description' => '',
            ],
            [
                'camo_activity_rate_id' => 10,
                'name' => 'GESTION ADMINISTRATIVA',
                'description' => '',
            ],
        ];
    }
}
