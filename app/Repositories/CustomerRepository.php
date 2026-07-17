<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function createRegistered(array $data): Customer
    {
        return Customer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'status' => 'Active',
        ]);
    }

    public function updateLastLogin(Customer $customer): void
    {
        $customer->update(['last_login' => now()]);
    }
}
