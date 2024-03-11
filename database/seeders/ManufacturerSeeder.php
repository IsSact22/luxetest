<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['acronym' => 'Boeing', 'name' => 'Boeing'],
            ['acronym' => 'Airbus', 'name' => 'Airbus'],
            ['acronym' => 'Embraer', 'name' => 'Embraer'],
            ['acronym' => 'BBD', 'name' => 'Bombardier Aerospace'],
            ['acronym' => 'MAC', 'name' => 'Mitsubishi Aircraft Corporation'],
            ['acronym' => 'COMAC', 'name' => 'Commercial Aircraft Corporation of China'],
            ['acronym' => 'Irkut Corporation', 'name' => 'Irkut Corporation'],
            ['acronym' => 'SCAC', 'name' => 'Sukhoi Civil Aircraft'],
            ['acronym' => 'ATR', 'name' => 'ATR'],
            ['acronym' => 'Antonov', 'name' => 'Antonov'],
            ['acronym' => 'Textron', 'name' => 'Textron Aviation'],
            ['acronym' => 'Pilatus', 'name' => 'Pilatus Aircraft'],
            ['acronym' => 'Piaggio', 'name' => 'Piaggio Aerospace'],
            ['acronym' => 'Viking', 'name' => 'Viking Air'],
            ['acronym' => 'BCH', 'name' => 'Beechcraft'],
            ['acronym' => 'Diamond', 'name' => 'Diamond Aircraft Industries'],
            ['acronym' => 'GSA', 'name' => 'Gulfstream Aerospace'],
            ['acronym' => 'CST', 'name' => 'Cessna Aircraft Company'],
            ['acronym' => 'LJR', 'name' => 'Learjet'],
            ['acronym' => 'Antonov Airlines', 'name' => 'Antonov Airlines'],
            ['acronym' => 'ACH', 'name' => 'Airbus Helicopter'],
            ['acronym' => 'BHI', 'name' => 'Bell Helicopter'],
            ['acronym' => 'RHC', 'name' => 'Robinson Helicopter company'],
        ];

        foreach ($data as $manufacturer) {
            Manufacturer::create($manufacturer);
        }

    }
}
