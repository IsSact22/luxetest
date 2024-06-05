<?php

namespace Database\Seeders;

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
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Citation CJ3+',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Citation Latitude',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Citation XLS+',
                        'engine_type' => 'turbo-fan',
                    ],
                ],
            ],
            // Bombardier
            [
                'brand' => 'Bombardier',
                'models' => [
                    [
                        'name' => 'Challenger 350',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Global 5000',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Global 6000',
                        'engine_type' => 'turbo-fan',
                    ],
                ],
            ],
            // Gulfstream
            [
                'brand' => 'Gulfstream',
                'models' => [
                    [
                        'name' => 'G280',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'G550',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'G650',
                        'engine_type' => 'turbo-fan',
                    ],
                ],
            ],
            // Embraer
            [
                'brand' => 'Embraer',
                'models' => [
                    [
                        'name' => 'Phenom 300',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Legacy 450',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Legacy 650',
                        'engine_type' => 'turbo-fan',
                    ],
                ],
            ],
            // Dassault
            [
                'brand' => 'Dassault',
                'models' => [
                    [
                        'name' => 'Falcon 2000LXS',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Falcon 900LX',
                        'engine_type' => 'turbo-fan',
                    ],
                    [
                        'name' => 'Falcon 7X',
                        'engine_type' => 'turbo-fan',
                    ],
                ],
            ],
        ];

        foreach ($models as $model) {
            $brand = \App\Models\BrandAircraft::query()->where('name', $model['brand'])->first();

            foreach ($model['models'] as $modelData) {
                $engineType = \App\Models\EngineType::query()->where('name', $modelData['engine_type'])->first();

                \App\Models\ModelAircraft::query()->create([
                    'name' => $modelData['name'],
                    'brand_aircraft_id' => $brand->id,
                    'engine_type_id' => $engineType->id,
                ]);
            }
        }
    }
}
