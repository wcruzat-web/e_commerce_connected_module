<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Customer::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'status' => 'Active',
            ]
        );

        Customer::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'Active',
            ]
        );
    }
}
