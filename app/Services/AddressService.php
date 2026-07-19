<?php
// AGNER — AddressService: added recipient_name, phone_number to create (ERPV0.2)

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Repositories\CustomerAddressRepository;

class AddressService
{
    public function __construct(
        private CustomerAddressRepository $addressRepository,
    ) {}

    public function getAddresses(Customer $customer)
    {
        return $customer->addresses;
    }

    public function saveOrUpdate(Customer $customer, array $data): CustomerAddress
    {
        if (!empty($data['address_id'])) {
            $address = $this->addressRepository->findByCustomerAndId(
                $customer->customer_id,
                $data['address_id']
            );

            if (!$address) {
                abort(404, 'Address not found.');
            }

            return $this->addressRepository->update($address, [
                'address_type' => $data['address_type'],
                'street' => $data['street'],
                'barangay' => $data['barangay'],
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'country' => $data['country'],
            ]);
        }

        $existing = $this->addressRepository->findExact($customer->customer_id, $data);

        if ($existing) {
            return $existing;
        }

        $hasExisting = $this->addressRepository->countByCustomer($customer->customer_id) > 0;

        // CHANGES HERE: added recipient_name and phone_number (NOT NULL columns)
        return $this->addressRepository->create([
            'customer_id' => $customer->customer_id,
            'address_type' => $data['address_type'],
            'recipient_name' => $data['recipient_name'] ?? ($customer->first_name.' '.$customer->last_name),
            'phone_number' => $data['phone_number'] ?? $customer->phone_number ?? '',
            'street' => $data['street'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'is_default' => !$hasExisting,
        ]);
    }

    public function saveFromOrder(Customer $customer, array $data): void
    {
        $existing = $this->addressRepository->findExact($customer->customer_id, $data);

        if ($existing) {
            return;
        }

        $hasExisting = $this->addressRepository->countByCustomer($customer->customer_id) > 0;

        // CHANGES HERE: added recipient_name and phone_number (NOT NULL columns)
        $this->addressRepository->create([
            'customer_id' => $customer->customer_id,
            'address_type' => $data['address_type'] ?? 'Home',
            'recipient_name' => $data['recipient_name'] ?? ($customer->first_name.' '.$customer->last_name),
            'phone_number' => $data['phone_number'] ?? $customer->phone_number ?? '',
            'street' => $data['street'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'is_default' => !$hasExisting,
        ]);
    }
}
