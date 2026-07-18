<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();

        if (!$customer) return;

        CustomerAddress::create([
            'customer_id' => $customer->customer_id,
            'address_type' => 'Home',
            'street' => '123 Tech Boulevard',
            'barangay' => 'Silicon Valley',
            'city' => 'San Francisco',
            'province' => 'California',
            'postal_code' => '94105',
            'country' => 'United States',
            'is_default' => true,
        ]);

        CustomerAddress::create([
            'customer_id' => $customer->customer_id,
            'address_type' => 'Work',
            'street' => '456 Innovation Drive',
            'barangay' => 'Tech Park',
            'city' => 'San Jose',
            'province' => 'California',
            'postal_code' => '95110',
            'country' => 'United States',
            'is_default' => false,
        ]);

        CustomerAddress::create([
            'customer_id' => $customer->customer_id,
            'address_type' => 'Other',
            'street' => '789 Startup Lane',
            'barangay' => 'Enterprise Zone',
            'city' => 'Palo Alto',
            'province' => 'California',
            'postal_code' => '94301',
            'country' => 'United States',
            'is_default' => false,
        ]);
    }
}
