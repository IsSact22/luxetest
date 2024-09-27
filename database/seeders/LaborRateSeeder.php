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
                'name' => 'Horas de Trabajo TURBO-FAN',
                'mount' => 80,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => EngineType::class,
                'code' => 'I06',
                'name' => 'Horas de Trabajo - PISTON',
                'mount' => 60,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => EngineType::class,
                'code' => 'I07',
                'name' => 'Horas de Trabajo - TURBINAS',
                'mount' => 90,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I08',
                'name' => 'Horas de Trabajo Aviónica',
                'mount' => 60,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I09',
                'name' => 'Horas de Trabajo Eléctrico',
                'mount' => 50,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I10',
                'name' => 'Horas de Trabajo Laminado',
                'mount' => 45,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I11',
                'name' => 'Horas de Trabajo Pintura',
                'mount' => 50,
            ],
            [
                'rateable_id' => 2,
                'rateable_type' => AdminRate::class,
                'code' => 'I20',
                'name' => 'Venta de repuestos',
                'mount' => 0,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => AdminRate::class,
                'code' => 'I25',
                'name' => 'Alquiler de Equipos y Servicio Técnico',
                'mount' => 0,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I30',
                'name' => 'Servicio de Ingeniería y Planificación',
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
                'name' => 'Alquiler de espacios',
                'mount' => 0,
            ],
            [
                'rateable_id' => 4,
                'rateable_type' => AdminRate::class,
                'code' => 'I90',
                'name' => 'Gestión Administrativa',
                'mount' => 0,
            ],
        ];
        foreach ($laborRates as $camoRate) {
            LaborRate::query()->create($camoRate);
        }
    }
}
