<?php

namespace Database\Seeders;

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
            'turbo-fan',
            'jet',
        ];
        foreach ($motorTypes as $type) {
            \App\Models\EngineType::query()->create(['name' => $type]);
        }
    }
}
