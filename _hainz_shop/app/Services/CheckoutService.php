<?php

namespace App\Services;

use App\DTOs\CheckoutDataDTO;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\OrderRepository;

class CheckoutService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartService $cartService,
    ) {}

    public function createOrder(Cart $cart, CheckoutDataDTO $data): Order
    {
        $summary = $this->cartService->getSummary($cart);

        $orderNumber = 'OID-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT) . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $order = $this->orderRepository->create([
            'customer_id' => $cart->customer_id,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'subtotal' => $summary->subtotal,
            'tax' => $summary->tax,
            'grand_total' => $summary->grandTotal,
            'shipping_name' => $data->shippingName,
            'shipping_email' => $data->shippingEmail,
            'shipping_phone' => $data->shippingPhone,
            'shipping_address' => $data->shippingAddress,
            'notes' => $data->notes,
        ]);

        foreach ($cart->items as $item) {
            $this->orderRepository->addItem($order, [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ]);
        }

        return $this->orderRepository->loadItems($order);
    }
}
