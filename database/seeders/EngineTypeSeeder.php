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
            'turbo-fan',
            'jet',
        ];
        foreach ($motorTypes as $type) {
            EngineType::create(['name' => $type]);
        }
    }
}
