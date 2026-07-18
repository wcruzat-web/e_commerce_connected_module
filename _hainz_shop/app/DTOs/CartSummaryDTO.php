<?php

namespace App\DTOs;

class CartSummaryDTO
{
    public function __construct(
        public readonly int   $itemsCount,
        public readonly float $subtotal,
        public readonly float $tax,
        public readonly float $grandTotal,
    ) {}
}
