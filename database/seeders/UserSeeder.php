<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
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
            'type' => 'user',
            'name' => 'Laymont Arratia',
            'password' => Hash::make('12215358'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin = User::find(1);
        $superAdmin->syncRoles('Super-Admin');

        User::factory(3)
            ->setToUser()
            ->create();
        $adminIds = User::whereIn('id', [2, 3, 4])->pluck('id');
        User::whereIn('id', $adminIds)->each(function ($admin) {
            $admin->syncRoles('Admin');
        });

        User::factory(3)
            ->setToUser()
            ->create();

        $userIds = User::whereIn('id', [5, 6, 7])->pluck('id');
        User::whereIn('id', $userIds)->each(function ($user) {
            $user->syncRoles('User');
        });

        $faker = Faker::create();
        User::factory(7)
            ->setToClient()
            ->create()
            ->each(function (User $user) use ($faker) {
                $clientProfile = $user->clientProfile()->make();
                $clientProfile->customer_name = $faker->company;
                $clientProfile->phone = $faker->phoneNumber;
                $clientProfile->save();
            });

        $clientIds = User::whereIn('id', [8, 9, 10, 11, 12, 13, 14])->pluck('id');
        User::whereIn('id', $clientIds)->each(function ($client) {
            $client->syncRoles('Client');
        });

    }
}
