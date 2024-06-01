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
                'name' => 'Technical Service',
                'description' => 'Technical Service',
            ],
            [
                'id' => 2,
                'name' => 'Sales',
                'description' => 'Sales',
            ],
            [
                'id' => 3,
                'name' => 'Rent',
                'description' => 'Equipment Rental + Technical Service',
            ],
            [
                'id' => 4,
                'name' => 'Management',
                'description' => 'Administrative Management',
            ],
        ];
        foreach ($adminRates as $adminRate) {
            AdminRate::query()->create($adminRate);
        }
    }
}
