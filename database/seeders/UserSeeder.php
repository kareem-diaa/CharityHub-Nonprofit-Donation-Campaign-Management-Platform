<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Admin',
                'email'             => 'admin@charityhub.local',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'role'              => 'admin',
            ],
            [
                'name'              => 'Campaign Manager',
                'email'             => 'manager@charityhub.local',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'role'              => 'manager',
            ],
            [
                'name'              => 'Test Donor',
                'email'             => 'donor@charityhub.local',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'role'              => 'donor',
            ],
            [
                'name'              => 'Test Volunteer',
                'email'             => 'volunteer@charityhub.local',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'role'              => 'volunteer',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->assignRole($role);
        }
    }
}
