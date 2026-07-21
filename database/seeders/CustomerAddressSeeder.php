<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::where('role', 'customer')->where('status', 'Active')->get();

        $addresses = [
            [
                'address_type' => 'Home',
                'recipient_name' => 'Juan Dela Cruz',
                'phone_number' => '09151234567',
                'street' => '123 Rizal Street',
                'barangay' => 'Barangay San Antonio',
                'city' => 'Quezon City',
                'province' => 'Metro Manila',
                'postal_code' => '1105',
                'country' => 'Philippines',
                'is_default' => true,
            ],
            [
                'address_type' => 'Work',
                'recipient_name' => 'Juan Dela Cruz',
                'phone_number' => '09151234567',
                'street' => '456 Ayala Avenue',
                'barangay' => 'Barangay San Lorenzo',
                'city' => 'Makati City',
                'province' => 'Metro Manila',
                'postal_code' => '1226',
                'country' => 'Philippines',
                'is_default' => false,
            ],
            [
                'address_type' => 'Home',
                'recipient_name' => 'Maria Santos',
                'phone_number' => '09261234567',
                'street' => '789 Mabini Street',
                'barangay' => 'Barangay Poblacion',
                'city' => 'Cebu City',
                'province' => 'Cebu',
                'postal_code' => '6000',
                'country' => 'Philippines',
                'is_default' => true,
            ],
            [
                'address_type' => 'Home',
                'recipient_name' => 'Ana Gonzales',
                'phone_number' => '09481234567',
                'street' => '321 MacArthur Highway',
                'barangay' => 'Barangay Dau',
                'city' => 'Angeles City',
                'province' => 'Pampanga',
                'postal_code' => '2009',
                'country' => 'Philippines',
                'is_default' => true,
            ],
            [
                'address_type' => 'Home',
                'recipient_name' => 'Luisa Fernandez',
                'phone_number' => '09651234567',
                'street' => '654 Bonifacio Drive',
                'barangay' => 'Barangay Bagumbayan',
                'city' => 'Davao City',
                'province' => 'Davao del Sur',
                'postal_code' => '8000',
                'country' => 'Philippines',
                'is_default' => true,
            ],
        ];

        foreach ($customers as $i => $customer) {
            $addr = $addresses[$i % count($addresses)];
            CustomerAddress::updateOrCreate(
                [
                    'customer_id' => $customer->customer_id,
                    'address_type' => $addr['address_type'],
                ],
                $addr
            );
        }
    }
}
