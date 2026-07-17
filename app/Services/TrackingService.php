<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class TrackingService
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function findByOrderNumberForCustomer(string $number, int $customerId): ?Order
    {
        if (str_starts_with($number, 'TRS-')) {
            return $this->orderRepository->findByTrackingNumberAndCustomer($number, $customerId);
        }
        return $this->orderRepository->findByOrderNumberAndCustomer($number, $customerId);
    }

    public function findById(int $orderId): ?Order
    {
        return $this->orderRepository->find($orderId);
    }

    public function buildTimeline(Order $order): array
    {
        $steps = [];

        $steps[] = [
            'title' => 'Order Placed',
            'description' => 'Your order has been received and confirmed.',
            'meta' => 'ShopEase - Online',
            'date' => $order->created_at->format('F d, Y'),
            'time' => $order->created_at->format('g:i A'),
            'state' => 'done',
        ];

        if ($order->payment_status === 'paid') {
            $steps[] = [
                'title' => 'Payment Confirmed',
                'description' => 'Payment has been verified and confirmed.',
                'meta' => 'ShopEase - Online',
                'date' => $order->paid_at->format('F d, Y'),
                'time' => $order->paid_at->format('g:i A'),
                'state' => 'done',
            ];
        }

        $tracking = $order->tracking;

        if ($tracking) {
            $statusOrder = ['Order Placed', 'Processing', 'Shipped', 'In Transit', 'Out for Delivery', 'Delivered'];
            $currentIdx = array_search($tracking->order_status, $statusOrder);

            foreach ($statusOrder as $i => $status) {
                $isDone = $i < $currentIdx;
                $isCurrent = $i === $currentIdx;

                $meta = $isCurrent || $isDone ? $tracking->courier_name : '';
                if (in_array($status, ['Out for Delivery', 'Delivered']) && ($isCurrent || $isDone)) {
                    $meta = $order->shipping_address;
                }

                $steps[] = [
                    'title' => $status,
                    'description' => $this->statusDescription($status),
                    'meta' => $meta,
                    'date' => $isCurrent || $isDone ? ($i === 0 ? $order->created_at->format('F d, Y') : $tracking->last_updated->format('F d, Y')) : '',
                    'time' => $isCurrent || $isDone ? ($i === 0 ? $order->created_at->format('g:i A') : $tracking->last_updated->format('g:i A')) : '',
                    'state' => $isDone ? 'done' : ($isCurrent ? 'current' : 'pending'),
                ];
            }
        } else {
            $allStatuses = ['Processing', 'Shipped', 'In Transit', 'Out for Delivery', 'Delivered'];
            $statusSeq = ['pending', 'processing', 'shipped', 'delivered'];
            $current = $order->status;

            foreach ($allStatuses as $i => $status) {
                $seqIndex = $i + 1;
                $statusKey = $statusSeq[$seqIndex] ?? $statusSeq[count($statusSeq) - 1];
                $isDone = array_search($statusKey, $statusSeq) < array_search($current, $statusSeq);
                $isCurrent = $statusKey === $current;

                $steps[] = [
                    'title' => $status,
                    'description' => $this->statusDescription($status),
                    'meta' => '',
                    'date' => '',
                    'time' => '',
                    'state' => $isDone ? 'done' : ($isCurrent ? 'current' : 'pending'),
                ];
            }
        }

        return $steps;
    }

    private function statusDescription(string $status): string
    {
        return match ($status) {
            'Order Placed' => 'Your order has been received and confirmed.',
            'Processing' => 'Our warehouse team is packaging your items.',
            'Shipped' => 'Package handed off to the carrier.',
            'In Transit' => 'Your package is on the way.',
            'Out for Delivery' => 'Package loaded onto delivery vehicle.',
            'Delivered' => 'Package delivered to your door.',
            default => 'Status update pending.',
        };
    }
}
