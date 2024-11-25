<?php

namespace Database\Seeders;

use App\Models\AdminRate;
use App\Models\EngineType;
use App\Models\LaborRate;
use App\Models\LaborRateValue;
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
                'amount' => 80,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => EngineType::class,
                'code' => 'I06',
                'name' => 'Horas de Trabajo - PISTON',
                'amount' => 60,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => EngineType::class,
                'code' => 'I07',
                'name' => 'Horas de Trabajo - TURBINAS',
                'amount' => 90,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I08',
                'name' => 'Horas de Trabajo Aviónica',
                'amount' => 60,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I09',
                'name' => 'Horas de Trabajo Eléctrico',
                'amount' => 50,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I10',
                'name' => 'Horas de Trabajo Laminado',
                'amount' => 45,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I11',
                'name' => 'Horas de Trabajo Pintura',
                'amount' => 50,
            ],
            [
                'rateable_id' => 2,
                'rateable_type' => AdminRate::class,
                'code' => 'I20',
                'name' => 'Venta de repuestos',
                'amount' => 0,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => AdminRate::class,
                'code' => 'I25',
                'name' => 'Alquiler de Equipos y Servicio Técnico',
                'amount' => 0,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I30',
                'name' => 'Servicio de Ingeniería y Planificación',
                'amount' => 0,
            ],
            [
                'rateable_id' => 1,
                'rateable_type' => AdminRate::class,
                'code' => 'I35',
                'name' => 'CAM',
                'amount' => 0,
            ],
            [
                'rateable_id' => 3,
                'rateable_type' => AdminRate::class,
                'code' => 'I40',
                'name' => 'Alquiler de espacios',
                'amount' => 0,
            ],
            [
                'rateable_id' => 4,
                'rateable_type' => AdminRate::class,
                'code' => 'I90',
                'name' => 'Gestión Administrativa',
                'amount' => 0,
            ],
        ];
        foreach ($laborRates as $camoRate) {
            // Crear el LaborRate
            $laborRate = LaborRate::query()->create([
                'rateable_id' => $camoRate['rateable_id'],
                'rateable_type' => $camoRate['rateable_type'],
                'code' => $camoRate['code'],
                'name' => $camoRate['name'],
            ]);

            // Añadir el valor del LaborRate en labor_rate_values
            LaborRateValue::query()->create([
                'labor_rate_id' => $laborRate->id,
                //'date' => now()->toDateString(),
                'amount' => $camoRate['amount'],
            ]);
        }
    }
}
