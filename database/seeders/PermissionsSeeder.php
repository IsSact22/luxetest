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

        // Create Roles
        Role::create(['name' => 'super-admin']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);

        Role::create(['name' => 'cam']);

        $user = Role::create(['name' => 'owner']);
        $user->givePermissionTo([
            'read',
            'update',
        ]);

        $client = Role::create(['name' => 'crew']);
        $client->givePermissionTo([
            'read',
        ]);
    }
}
