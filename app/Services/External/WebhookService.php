<?php

namespace App\Services\External;

use App\Models\Order;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;

class WebhookService
{
    public function orderCreated(Order $order): void
    {
        $payload = [
            'event' => 'order.created',
            'order_number' => $order->order_number,
            'customer' => [
                'name' => $order->shipping_name,
                'email' => $order->shipping_email,
            ],
            'grand_total' => $order->grand_total,
            'items' => $order->items->map(fn ($item) => [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ]),
        ];

        $this->dispatch('finance', 'order.created', $payload);
        $this->dispatch('sales', 'order.created', $payload);
    }

    public function paymentConfirmed(Order $order): void
    {
        $payload = [
            'event' => 'payment.confirmed',
            'order_number' => $order->order_number,
            'finance_transaction_id' => $order->finance_transaction_id,
            'paid_at' => $order->paid_at,
        ];

        $this->dispatch('sales', 'payment.confirmed', $payload);
    }

    private function dispatch(string $module, string $event, array $payload): void
    {
        $url = config("external-modules.{$module}.webhook_url");

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

            WebhookLog::create([
                'target_module' => $module,
                'event' => $event,
                'payload' => $payload,
                'response_status' => $response->status(),
                'response_body' => $response->body(),
                'success' => $response->successful(),
            ]);
        } catch (\Exception $e) {
            WebhookLog::create([
                'target_module' => $module,
                'event' => $event,
                'payload' => $payload,
                'response_status' => null,
                'response_body' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }
}
