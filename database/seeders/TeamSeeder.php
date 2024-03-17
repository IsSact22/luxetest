<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'ALPHA', 'user_id' => 4],
            ['name' => 'BETA', 'user_id' => 5],
            ['name' => 'CHARLIE', 'user_id' => 6],
        ])->each(fn ($team) => Team::create([
            'name' => $team->name,
            'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing',
            'user_id' => $team->user_id,
        ]));

        //Team::find(1);

    }
}
