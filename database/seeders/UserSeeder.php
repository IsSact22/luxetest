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
        User::firstOrCreate([
            'email' => 'superadmin@luxeplus.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin = User::find(1);
        $superAdmin->syncRoles('super-admin');

        // Crear Admin
        User::factory()
            ->create([
                'name' => 'Mara Beltran',
                'email' => 'mara@luexplus.com',
            ]);
        $admin = User::find(2);
        $admin->syncRoles('admin');

        // Crear Cams
        User::factory()
            ->create([
                'name' => 'Angel Contreras',
                'email' => 'angel@luexplus.com',
            ]);

        User::factory()
            ->create([
                'name' => 'Oscar Rodriguez',
                'email' => 'oscar@luexplus.com',
            ]);

        $cams = User::whereIn('id', [3, 4])->pluck('id');
        User::whereIn('id', $cams)->each(function ($admin) {
            $admin->syncRoles('cam');
        });

        $users = User::factory(3)
            ->create();

        $ownerIds = [5, 6, 7];
        $owners = $users->whereIn('id', $ownerIds);
        $owners->each(function ($user) {
            $user->syncRoles('owner');
            $crew = User::factory()->create(['owner_id' => $user->id]);
            $crew->syncRoles('crew');
        });

        // User Admin Test
        $userAdminTest = User::factory()
            ->create([
                'name' => 'Admin Test',
                'email' => 'admin@luexplus.com',
            ]);
        $userAdminTest->syncRoles('admin');

        // User Cam or Project Manager Test
        $userCamTest = User::factory()
            ->create([
                'name' => 'Cam Test',
                'email' => 'cam@luexplus.com',
            ]);
        $userCamTest->syncRoles('cam');

        // User Owner Test
        $userOwnerTest = User::factory()
            ->create([
                'name' => 'Owner Test',
                'email' => 'owner@luexplus.com',
            ]);
        $userOwnerTest->syncRoles('owner');

        // User Crew Test
        $userCrewTest = User::factory()
            ->create([
                'name' => 'Crew Test',
                'email' => 'crew@luexplus.com',
            ]);
        $userCrewTest->syncRoles('crew');
    }
}
