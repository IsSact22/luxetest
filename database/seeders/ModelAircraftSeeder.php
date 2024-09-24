<?php

namespace Database\Seeders;

use App\Models\BrandAircraft;
use App\Models\EngineType;
use App\Models\ModelAircraft;
use Illuminate\Database\Seeder;

class ModelAircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // Cessna
            [
                'brand' => 'Cessna',
                'models' => [
                    [
                        'name' => 'Citation II',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Citation CJ3+',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Citation Latitude',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Citation XLS+',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                ],
            ],
            // Bombardier
            [
                'brand' => 'Bombardier',
                'models' => [
                    [
                        'name' => 'Challenger 350',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Global 5000',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Global 6000',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                ],
            ],
            // Gulfstream
            [
                'brand' => 'Gulfstream',
                'models' => [
                    [
                        'name' => 'G280',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'G550',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'G650',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                ],
            ],
            // Embraer
            [
                'brand' => 'Embraer',
                'models' => [
                    [
                        'name' => 'Phenom 300',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Legacy 450',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Legacy 650',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                ],
            ],
            // Dassault
            [
                'brand' => 'Dassault',
                'models' => [
                    [
                        'name' => 'Falcon 2000LXS',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Falcon 900LX',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                    [
                        'name' => 'Falcon 7X',
                        'engine_type' => 'turbo-fan/turbo-jet',
                    ],
                ],
            ],
        ];

        foreach ($models as $model) {
            $brand = BrandAircraft::query()->where('name', $model['brand'])->first();

            foreach ($model['models'] as $modelData) {
                $engineType = EngineType::query()->where('name', $modelData['engine_type'])->first();

                ModelAircraft::query()->create([
                    'name' => $modelData['name'],
                    'brand_aircraft_id' => $brand->id,
                    'engine_type_id' => $engineType->id,
                ]);
            }
        }
    }
}
