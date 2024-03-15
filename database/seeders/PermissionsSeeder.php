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
        Role::create(['name' => 'Super-Admin']);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);

        $user = Role::create(['name' => 'User']);
        $user->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);

        $client = Role::create(['name' => 'Client']);
        $client->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
