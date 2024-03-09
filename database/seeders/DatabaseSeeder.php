<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            //TeamSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Laymont Arratia',
            'email' => 'laymont@gmail.com',
            'password' => Hash::make('12215358'),
        ]);

        User::factory(14)->create();

        $superAdmin = User::find(1);
        $superAdmin->assignRole('Super-Admin');

        // Admin
        User::whereIn('id', [2, 3])->each(function ($admin) {
            $admin->assignRole('Admin');
        });

        // Inspector
        User::whereIn('id', [4, 5, 6])->each(function ($inspector) {
            $inspector->assignRole('Inspector');
        });

        // Supervisor
        User::whereIn('id', [7, 8, 9])->each(function ($supervisor) {
            $supervisor->assignRole('Supervisor-Mechanic');
        });

        // Mechanic
        User::where('id', '>', 9)->each(function ($mechanic) {
            $mechanic->assignRole('Mechanic');
        });
    }
}
