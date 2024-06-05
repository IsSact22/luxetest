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

        // Force-delete
        Permission::create(['name' => 'restore', 'guard_name' => 'web']);
        Permission::create(['name' => 'force-delete', 'guard_name' => 'web']);

        // Role
        Permission::create(['name' => 'create-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-role', 'guard_name' => 'web']);

        // Permission
        Permission::create(['name' => 'create-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-permission', 'guard_name' => 'web']);

        // User
        Permission::create(['name' => 'create-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-user', 'guard_name' => 'web']);

        // Profile
        Permission::create(['name' => 'profile-user', 'guard_name' => 'web']);

        // Engine Type
        Permission::create(['name' => 'create-engine-type', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-engine-type', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-engine-type', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-engine-type', 'guard_name' => 'web']);

        // Brand Aircraft
        Permission::create(['name' => 'create-brand-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-brand-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-brand-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-brand-aircraft', 'guard_name' => 'web']);

        // Model Aircraft
        Permission::create(['name' => 'create-model-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-model-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-model-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-model-aircraft', 'guard_name' => 'web']);

        // Aircraft
        Permission::create(['name' => 'create-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-aircraft', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-aircraft', 'guard_name' => 'web']);

        // Rates
        Permission::create(['name' => 'create-rate', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-rate', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-rate', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-rate', 'guard_name' => 'web']);

        // Camo
        Permission::create(['name' => 'create-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-camo', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-camo', 'guard_name' => 'web']);

        // Activity
        Permission::create(['name' => 'create-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-activity', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-activity', 'guard_name' => 'web']);

        // Basic
        Permission::create(['name' => 'create', 'guard_name' => 'web']);
        Permission::create(['name' => 'read', 'guard_name' => 'web']);
        Permission::create(['name' => 'update', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete', 'guard_name' => 'web']);

        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'profile-user',
            'create-role',
            'read-role',
            'update-role',
            'delete-role',
            'create-permission',
            'read-permission',
            'update-permission',
            'delete-permission',
            'create-user',
            'read-user',
            'update-user',
            'create-rate',
            'read-rate',
            'update-rate',
            'delete-rate',
            'create-engine-type',
            'read-engine-type',
            'update-engine-type',
            'delete-engine-type',
            'create-brand-aircraft',
            'read-brand-aircraft',
            'update-brand-aircraft',
            'delete-brand-aircraft',
            'create-model-aircraft',
            'read-model-aircraft',
            'update-model-aircraft',
            'delete-model-aircraft',
            'create-aircraft',
            'read-aircraft',
            'update-aircraft',
            'delete-aircraft',
            'read-camo',
            'update-camo',
            'create-activity',
            'read-activity',
            'update-activity',
        ]);

        $cam = Role::create(['name' => 'cam']);
        $cam->givePermissionTo([
            'profile-user',
            'create-engine-type',
            'read-engine-type',
            'update-engine-type',
            'create-brand-aircraft',
            'read-brand-aircraft',
            'update-brand-aircraft',
            'create-model-aircraft',
            'read-model-aircraft',
            'update-model-aircraft',
            'create-aircraft',
            'read-aircraft',
            'update-aircraft',
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
            'read-activity',
            'update-activity',
        ]);

        $client = Role::create(['name' => 'crew']);
        $client->givePermissionTo([
            'profile-user',
            'read-camo',
            'read-activity',
            'update-activity',
        ]);
    }
}
