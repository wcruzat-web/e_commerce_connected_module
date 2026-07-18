<?php

namespace App\Http\Controllers;

use App\Services\TrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function __construct(
        private TrackingService $trackingService,
    ) {}

    public function index()
    {
        $customer = Auth::user();
        $orderId = session('order_id');

        if ($orderId) {
            $order = $this->trackingService->findById($orderId);

            if ($order && $order->customer_id === $customer->customer_id) {
                $timelineSteps = $this->trackingService->buildTimeline($order);
                return view('pages.customer.order-tracking.tracking', compact('order', 'timelineSteps'));
            }
        }

        return view('pages.customer.order-tracking.tracking');
    }

    public function track(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('tracking');
        }

        $validated = $request->validate([
            'order_number' => 'required|string|regex:/^OID-\d{4}-\d{4}$/|max:255',
        ]);

        $customer = Auth::user();
        $order = $this->trackingService->findByOrderNumberForCustomer($validated['order_number'], $customer->customer_id);

        if (!$order) {
            return redirect()->route('tracking')->with('error', 'Order not found. Please check your order number and try again.');
        }

        session()->forget('order_id');

        $timelineSteps = $this->trackingService->buildTimeline($order);

        return view('pages.customer.order-tracking.tracking', compact('order', 'timelineSteps'));
    }
}
