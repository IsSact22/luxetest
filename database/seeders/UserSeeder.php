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
        $cams = User::whereIn('id', [2, 3])->pluck('id');
        User::whereIn('id', $cams)->each(function ($admin) {
            $admin->syncRoles('cam');
        });

        $users = User::factory(3)
            ->create();

        $ownerIds = [4, 5, 6];
        $owners = $users->whereIn('id', $ownerIds);
        $owners->each(function ($user) {
            $user->syncRoles('owner');
            $crew = User::factory()->create(['owner_id' => $user->id]);
            $crew->syncRoles('crew');
        });
    }
}
