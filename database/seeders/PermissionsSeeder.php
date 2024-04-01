<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'read']);
        Permission::create(['name' => 'update']);
        Permission::create(['name' => 'delete']);

        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'read-user']);
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'delete-user']);

        Permission::create(['name' => 'profile-user']);

        Permission::create(['name' => 'create-camo']);
        Permission::create(['name' => 'read-camo']);
        Permission::create(['name' => 'update-camo']);
        Permission::create(['name' => 'delete-camo']);

        Permission::create(['name' => 'create-activity']);
        Permission::create(['name' => 'read-activity']);
        Permission::create(['name' => 'update-activity']);
        Permission::create(['name' => 'delete-activity']);

        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'create-user',
            'read-user',
            'update-user',
            'profile-user',
            'create',
            'read',
            'update',
            'delete',
            'create-user',
            'read-user',
            'update-user',
            'create-camo',
            'read-camo',
            'update-camo',
        ]);

        $cam = Role::create(['name' => 'cam']);
        $cam->givePermissionTo([
            'profile-user',
            'create-camo',
            'read-camo',
            'update-camo',
            'create-activity',
            'read-activity',
            'update-activity',
        ]);

        $user = Role::create(['name' => 'owner']);
        $user->givePermissionTo([
            'profile-user',
            'read-camo',
            'update-camo',
            'read-activity',
            'update-activity',
        ]);

        $client = Role::create(['name' => 'crew']);
        $client->givePermissionTo([
            'profile-user',
            'read-camo',
            'read-activity',
        ]);
    }
}
