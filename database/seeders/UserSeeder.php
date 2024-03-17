<?php

namespace Database\Seeders;

use App\Models\Client;
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
        User::firstOrCreate([
            'email' => 'laymont@gmail.com',
        ], [
            'name' => 'Laymont Arratia',
            'password' => Hash::make('12215358'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin = User::find(1);
        $superAdmin->syncRoles('super-admin');

        User::factory(2)
            ->create();
        $adminIds = User::whereIn('id', [2, 3])->pluck('id');
        User::whereIn('id', $adminIds)->each(function ($admin) {
            $admin->syncRoles('admin');
        });

        User::factory(3)
            ->create();

        $userIds = User::whereIn('id', [4, 5, 6])->pluck('id');
        User::whereIn('id', $userIds)->each(function ($user) {
            $user->syncRoles('project-manager');
        });

        User::factory(8)
            ->create();
        $clientsIds = User::where('id', '>', 6)->pluck('id');
        User::whereIn('id', $clientsIds)->each(function ($user) {
            $user->syncRoles('client');
            $client = Client::factory()->create();
            $user->clients()->attach($client);
        });

    }
}
