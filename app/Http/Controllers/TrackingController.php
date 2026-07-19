<?php
// CRUZAT — tracking: real-time polling, customer_received (ERPV0.2.2, ERPV0.2.4)

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
            'order_number' => ['required', 'string', 'regex:/^(OID-\d{4}-\d{4}|TRS-\d{3}-\d{3})$/', 'max:255'],
        ]);

        $customer = Auth::user();
        $order = $this->trackingService->findByOrderNumberForCustomer($validated['order_number'], $customer->customer_id);

        if (!$order) {
            return redirect()->route('tracking')->with('error', 'Order not found. Please check your order or tracking number and try again.');
        }

        session()->forget('order_id');

        $timelineSteps = $this->trackingService->buildTimeline($order);

        return view('pages.customer.order-tracking.tracking', compact('order', 'timelineSteps'));
    }

    public function show(int $orderId)
    {
        $order = $this->trackingService->findById($orderId);

        if (!$order || $order->customer_id !== Auth::user()->customer_id) {
            return redirect()->route('tracking')->with('error', 'Order not found.');
        }

        session()->put('order_id', $orderId);

        return redirect()->route('tracking');
    }

    // [AGNER] AJAX poll endpoint for live timeline updates
    public function poll(int $orderId)
    {
        $order = $this->trackingService->findById($orderId);

        if (!$order || $order->customer_id !== Auth::user()->customer_id) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $timelineSteps = $this->trackingService->buildTimeline($order);

        return response()->json([
            'timeline_html'  => view('pages.customer.order-tracking.components.timeline', compact('timelineSteps'))->render(),
            'banner_html'    => view('pages.customer.order-tracking.components.order-status-banner', compact('order'))->render(),
            'meta_html'      => view('pages.customer.order-tracking.components.shipment-meta', compact('order'))->render(),
            'received_html'  => view('pages.customer.order-tracking.components.received-action', compact('order'))->render(),
        ]);
    }
}
