<?php

namespace Database\Seeders;

use App\Models\AdminRate;
use Illuminate\Database\Seeder;

class AdminRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRates = [
            [
                'id' => 1,
                'name' => 'lorem ipsum',
                'description' => 'lorem ipsum',
            ],
            [
                'id' => 2,
                'name' => 'lorem ipsum 1',
                'description' => 'lorem ipsum 1',
            ],
            [
                'id' => 3,
                'name' => 'lorem ipsum 2',
                'description' => 'lorem ipsum 2',
            ],
            [
                'id' => 4,
                'name' => 'lorem ipsum 3',
                'description' => 'lorem ipsum 3',
            ],
        ];
        foreach ($adminRates as $adminRate) {
            AdminRate::query()->create($adminRate);
        }
    }
}
