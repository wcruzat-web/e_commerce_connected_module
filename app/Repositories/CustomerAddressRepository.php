<?php

namespace App\Repositories;

use App\Models\CustomerAddress;

class CustomerAddressRepository
{
    public function findByCustomerAndId(int $customerId, int $addressId): ?CustomerAddress
    {
        return CustomerAddress::where('customer_id', $customerId)->find($addressId);
    }

    public function findExact(int $customerId, array $data): ?CustomerAddress
    {
        return CustomerAddress::where('customer_id', $customerId)
            ->where('street', $data['street'])
            ->where('barangay', $data['barangay'])
            ->where('city', $data['city'])
            ->where('province', $data['province'])
            ->where('postal_code', $data['postal_code'])
            ->where('country', $data['country'])
            ->first();
    }

    public function create(array $data): CustomerAddress
    {
        return CustomerAddress::create($data);
    }

    public function update(CustomerAddress $address, array $data): CustomerAddress
    {
        $address->update($data);
        return $address->fresh();
    }

    public function countByCustomer(int $customerId): int
    {
        return CustomerAddress::where('customer_id', $customerId)->count();
    }
}
