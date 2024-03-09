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

        Permission::create(['name' => 'create_inspections']);
        Permission::create(['name' => 'approve_inspections']);
        Permission::create(['name' => 'read_inspections']);
        Permission::create(['name' => 'update_inspections']);
        Permission::create(['name' => 'delete_inspections']);

        Permission::create(['name' => 'create_reports']);
        Permission::create(['name' => 'approve_report']);
        Permission::create(['name' => 'read_reports']);
        Permission::create(['name' => 'update_reports']);
        Permission::create(['name' => 'delete_reports']);

        Permission::create(['name' => 'create_damages']);
        Permission::create(['name' => 'approve_damages']);
        Permission::create(['name' => 'read_damages']);
        Permission::create(['name' => 'updated_damages']);
        Permission::create(['name' => 'delete_damages']);

        // Create Roles
        Role::create(['name' => 'Super-Admin']);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'create',
            'read',
            'update',
            'delete',
        ]);

        $inspector = Role::create(['name' => 'Inspector']);
        $inspector->givePermissionTo([
            'create_inspections',
            'approve_inspections',
            'read_inspections',
            'update_inspections',
            'delete_inspections',
            'create_reports',
            'approve_report',
            'read_reports',
            'update_reports',
            'delete_reports',
            'create_damages',
            'approve_damages',
            'read_damages',
            'updated_damages',
            'delete_damages',
        ]);

        $supervisorMechanic = Role::create(['name' => 'Supervisor-Mechanic']);
        $supervisorMechanic->givePermissionTo([
            'create_reports',
            'approve_report',
            'read_reports',
            'update_reports',
            'delete_reports',
            'create_damages',
            'approve_damages',
            'read_damages',
            'updated_damages',
            'delete_damages',
        ]);

        $mechanic = Role::create(['name' => 'Mechanic']);
        $mechanic->givePermissionTo([
            'create_reports',
            'read_reports',
            'update_reports',
            'delete_reports',
            'create_damages',
            'read_damages',
            'updated_damages',
            'delete_damages',
        ]);

        $backoffice = Role::create(['name' => 'Backoffice-User']);
        $backoffice->givePermissionTo([
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
        ]);

        $guestWeb = Role::create(['name' => 'Guest']);
        $guestWeb->givePermissionTo([
            'read',
        ]);
    }
}
