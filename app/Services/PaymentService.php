<?php

namespace App\Services;

use App\DTOs\PaymentDataDTO;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\OrderRepository;

class PaymentService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartService $cartService,
    ) {}

    public function processPayment(Cart $cart, PaymentDataDTO $data): Order
    {
        $summary = $this->cartService->getSummary($cart);

        $orderNumber = 'OID-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT) . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $order = $this->orderRepository->create([
            'customer_id' => $cart->customer_id,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $data->paymentMethod,
            'paid_at' => now(),
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

            $item->product->decrement('stock', $item->quantity);
        }

        $cart->items()->delete();

        $trackingNumber = 'TRS-' . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT) . '-' . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);

        $order->tracking()->create([
            'tracking_number' => $trackingNumber,
            'order_status' => 'Order Placed',
            'courier_name' => 'ShopEase Express',
            'shipped_from' => 'Bulacan, Philippines',
            'estimated_delivery_date' => now()->addDays(rand(5, 10)),
            'last_updated' => now(),
            'sync_status' => 'Pending',
        ]);

        return $this->orderRepository->loadItems($order);
    }
}
