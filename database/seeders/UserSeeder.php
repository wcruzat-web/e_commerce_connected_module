<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        Customer::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'status' => 'Active',
            ]
        );

        // Admin
        Customer::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'Active',
            ]
        );

        // Customer
        Customer::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'first_name' => 'Customer',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'Active',
            ]
        );
    }
}
