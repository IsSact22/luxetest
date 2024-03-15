<?php

namespace Database\Seeders;

use App\Models\AircraftModel;
use Illuminate\Database\Seeder;

class AircraftModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // Modelos de aviones
            [
                'manufacturer_id' => 18,
                'name' => 'Cessna 172',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'piston',
                'motor_qty' => 1,
                'passenger_qty' => 3,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Cessna 182',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'piston',
                'motor_qty' => 1,
                'passenger_qty' => 4,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Citation Mustang',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 4,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Citation CJ4',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 9,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Citation Latitude',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 9,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Citation XLS+',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 12,
            ],
            [
                'manufacturer_id' => 18,
                'name' => 'Citation Sovereign+',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 12,
            ],
            [
                'manufacturer_id' => 23,
                'name' => 'Robinson R44',
                'category' => 'rotorcraft',
                'class' => 'helicopter',
                'motor_type' => 'piston',
                'motor_qty' => 1,
                'passenger_qty' => 3,
            ],
            [
                'manufacturer_id' => 15,
                'name' => 'Beechcraft Bonanza G36',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'piston',
                'motor_qty' => 1,
                'passenger_qty' => 4,
            ],
            [
                'manufacturer_id' => 15,
                'name' => 'Beechcraft King Air 350',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'piston',
                'motor_qty' => 2,
                'passenger_qty' => 9,
            ],
            [
                'manufacturer_id' => 19,
                'name' => 'Learjet 75 Liberty',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 9,
            ],
            [
                'manufacturer_id' => 19,
                'name' => 'Learjet 60 XR',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 9,
            ],
            [
                'manufacturer_id' => 17,
                'name' => 'Gulfstream G650',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbine',
                'motor_qty' => 2,
                'passenger_qty' => 18,
            ],
            [
                'manufacturer_id' => 17,
                'name' => 'Gulfstream G550',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbine',
                'motor_qty' => 2,
                'passenger_qty' => 16,
            ],
            [
                'manufacturer_id' => 17,
                'name' => 'Gulfstream G650',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 18,
            ],
            [
                'manufacturer_id' => 17,
                'name' => 'Gulfstream G550',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 2,
                'passenger_qty' => 16,
            ],
            [
                'manufacturer_id' => 24,
                'name' => 'Dassault Falcon 900',
                'category' => 'aircraft',
                'class' => 'land',
                'motor_type' => 'turbofan',
                'motor_qty' => 3,
                'passenger_qty' => 19,
            ],
        ];

        foreach ($models as $model) {
            AircraftModel::create($model);
        }
    }
}
