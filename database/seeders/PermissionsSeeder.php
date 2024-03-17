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

        Role::create(['name' => 'project-manager']);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);

        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
