<?php

namespace Database\Seeders;

use App\Models\CamoActivityRate;
use Illuminate\Database\Seeder;

class CamoActivityRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            [
                'code' => 'I08',
                'name' => 'HORAS HOMBRE TALLER - AVIONICA',
                'mount' => 60,
            ],
            [
                'code' => 'I09',
                'name' => 'HORAS HOMBRE TALLER - ELECTRICA',
                'mount' => 50,
            ],
            [
                'code' => 'I10',
                'name' => 'HORAS HOMBRE TALLER - LAMINADO',
                'mount' => 45,
            ],
            [
                'code' => 'I11',
                'name' => 'HORAS HOMBRE TALLER - PINTURA',
                'mount' => 50,
            ],
            [
                'code' => 'I20',
                'name' => 'VENTA DE PARTES',
                'mount' => 0,
            ],
            [
                'code' => 'I25',
                'name' => 'ALQUILER DE EQUIPOS + SERVICIO TECNICO',
                'mount' => 0,
            ],
            [
                'code' => 'I30',
                'name' => 'SERV. INGENIERIA Y PLANIFICACION',
                'mount' => 0,
            ],
            [
                'code' => 'I35',
                'name' => 'CAM',
                'mount' => 0,
            ],
            [
                'code' => 'I40',
                'name' => 'ALQUILER DE ESPACIO',
                'mount' => 0,
            ],
            [
                'code' => 'I90',
                'name' => 'GESTION ADMINISTRATIVA',
                'mount' => 0,
            ],
        ];
        foreach ($rates as $rate) {
            CamoActivityRate::create($rate);
        }
    }
}
