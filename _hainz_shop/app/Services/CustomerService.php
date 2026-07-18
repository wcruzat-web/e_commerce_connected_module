<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function __construct(
        private CustomerRepository $customerRepository,
    ) {}

    public function authenticate(string $email, string $password, bool $remember = false): ?Customer
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $customer = Auth::user();
            $this->customerRepository->updateLastLogin($customer);
            return $customer;
        }

        return null;
    }

    public function register(array $data): Customer
    {
        $customer = $this->customerRepository->createRegistered($data);

        Auth::login($customer);

        return $customer;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
