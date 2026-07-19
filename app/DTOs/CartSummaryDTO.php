<?php

namespace App\DTOs;

class CartSummaryDTO
{
    public function __construct(
        public readonly int   $itemsCount,
        public readonly float $subtotal,
        public readonly float $shippingFee,
        public readonly float $tax,
        public readonly float $discount,
        public readonly float $grandTotal,
        public readonly ?string $couponCode = null,
        public readonly bool  $isFreeShipping = false,
        public readonly string $couponLabel = '',
        public readonly ?string $voucherStatus = null,
        public readonly ?string $voucherMessage = null,
    ) {}
}
