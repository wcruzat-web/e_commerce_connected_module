<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admins
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@example.com',
                'password' => 'password',
                'phone_number' => '09171234567',
                'role' => 'super_admin',
                'status' => 'Active',
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => 'password',
                'phone_number' => '09179876543',
                'role' => 'admin',
                'status' => 'Active',
            ],
            // Customers
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'customer@example.com',
                'password' => 'password',
                'phone_number' => '09151234567',
                'role' => 'customer',
                'status' => 'Active',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria@example.com',
                'password' => 'password',
                'phone_number' => '09261234567',
                'role' => 'customer',
                'status' => 'Active',
            ],
            [
                'first_name' => 'Jose',
                'last_name' => 'Rizal',
                'email' => 'jose@example.com',
                'password' => 'password',
                'phone_number' => '09371234567',
                'role' => 'customer',
                'status' => 'Active',
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Gonzales',
                'email' => 'ana@example.com',
                'password' => 'password',
                'phone_number' => '09481234567',
                'role' => 'customer',
                'status' => 'Active',
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Mendoza',
                'email' => 'pedro@example.com',
                'password' => 'password',
                'phone_number' => '09551234567',
                'role' => 'customer',
                'status' => 'Inactive',
            ],
            [
                'first_name' => 'Luisa',
                'last_name' => 'Fernandez',
                'email' => 'luisa@example.com',
                'password' => 'password',
                'phone_number' => '09651234567',
                'role' => 'customer',
                'status' => 'Active',
            ],
        ];

        foreach ($users as $user) {
            Customer::updateOrCreate(
                ['email' => $user['email']],
                [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'password' => Hash::make($user['password']),
                    'phone_number' => $user['phone_number'],
                    'role' => $user['role'],
                    'status' => $user['status'],
                ]
            );
        }
    }
}
