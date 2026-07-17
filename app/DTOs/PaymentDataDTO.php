<?php

namespace App\DTOs;

class PaymentDataDTO
{
    public function __construct(
        public readonly string $paymentMethod,
        public readonly string $shippingName,
        public readonly string $shippingEmail,
        public readonly string $shippingPhone,
        public readonly string $shippingAddress,
        public readonly string $notes,
    ) {}
}
