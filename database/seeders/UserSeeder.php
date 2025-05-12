<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Super-Admin
        $superAdmin = User::query()->firstOrCreate([
            'email' => 'superadmin@luxeservicesve.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'locale' => 'es',
        ]);

        $superAdmin->syncRoles('super-admin');

        // Crear Admin
        $admin = User::create([
            'name' => 'Mara Beltran',
            'email' => 'mbeltran@luxeservicesve.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'locale' => 'es',
        ]);

        $admin->syncRoles('admin');

        // Crear Cams
        $angel = User::create([
            'name' => 'Angel Contreras',
            'email' => 'acontreras@luxeservicesve.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'locale' => 'es',
        ]);

        $angel->syncRoles('cam');
    }
}
