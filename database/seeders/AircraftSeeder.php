<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aircraft::factory(7)->create();

        $faker = Faker::create();
        Aircraft::create([
            'owner_id' => 10,
            'aircraft_model_id' => 17,
            'name' => 'T7-77PR(F900EX-097)',
            'construction_date' => Carbon::parse('1991-07-05'),
            'serial' => $faker->unique()->bothify('??####?##???###'),
            'registration' => $faker->bothify('YV-######'),
            'flight_hours' => $faker->numberBetween(0, 3000),
        ]);
    }
}
