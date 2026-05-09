<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage_users',
            'edit_users',
            'view_users',
            'manage_campaigns',
            'view_campaigns',
            'manage_donations',
            'make_donations',
            'manage_volunteers',
            'register_volunteer',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleManager = Role::firstOrCreate(['name' => 'Manager']);
        $roleManager->givePermissionTo([
            'view_users',
            'manage_campaigns',
            'view_campaigns',
            'manage_donations',
            'manage_volunteers',
            'view_reports'
        ]);

        $roleEmployee = Role::firstOrCreate(['name' => 'Employee']);
        $roleEmployee->givePermissionTo([
            'edit_users', // from lecture 5
            'view_campaigns',
            'view_reports'
        ]);

        $roleDonor = Role::firstOrCreate(['name' => 'Donor']);
        $roleDonor->givePermissionTo([
            'view_campaigns',
            'make_donations',
            'register_volunteer'
        ]);

        $roleVolunteer = Role::firstOrCreate(['name' => 'Volunteer']);
        $roleVolunteer->givePermissionTo([
            'view_campaigns',
            'register_volunteer'
        ]);

        // Create default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@charityhub.local'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($roleAdmin);
    }
}
