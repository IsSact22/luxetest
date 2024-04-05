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
        Permission::create(['name' => 'create', 'guard_name' => 'web']);
        Permission::create(['name' => 'read', 'guard_name' => 'web']);
        Permission::create(['name' => 'update', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete', 'guard_name' => 'web']);
        Permission::create(['name' => 'restore', 'guard_name' => 'web']);
        Permission::create(['name' => 'force-delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'create-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-role', 'guard_name' => 'web']);

        Permission::create(['name' => 'create-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-permission', 'guard_name' => 'web']);

        Permission::create(['name' => 'create-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-user', 'guard_name' => 'web']);

        Permission::create(['name' => 'profile-user', 'guard_name' => 'web']);

        Permission::create(['name' => 'create-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-camo', 'guard_name' => 'web']);

        Permission::create(['name' => 'create-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-activity', 'guard_name' => 'web']);

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
