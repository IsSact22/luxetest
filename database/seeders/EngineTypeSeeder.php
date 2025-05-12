<?php

namespace Database\Seeders;

use App\Models\EngineType;
use Illuminate\Database\Seeder;

class EngineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motorTypes = [
            'piston',
            'turbo-prop',
            'turbo-fan/turbo-jet',
            'turbo-helice',
        ];
        foreach ($motorTypes as $type) {
            EngineType::query()->create(['name' => $type]);
        }
    }
}
