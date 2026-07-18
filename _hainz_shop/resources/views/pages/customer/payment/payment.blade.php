{{--
    ==================================================================
    ERP MODULE: Checkout — Payment (Payment Page)
    ------------------------------------------------------------------
    FRONTEND-ONLY IMPLEMENTATION — NO BACKEND LOGIC INCLUDED.

    This view composes the full Payment Page from
    page-scoped components stored alongside it in the
    pages/payment/components/ directory.

    Shared components (checkout stepper, voucher card) are
    pulled from pages/cart/components/ since they are identical.

    The site header / top navigation lives in
    resources/views/layouts/app.blade.php and is rendered above
    @yield('content').

    Only the Payment Page body is assembled here.

    TODO (Backend Integration):
      Controller: CheckoutController
      Method: showPayment() / placeOrder()
      Route: GET /checkout/payment
      Route: POST /checkout/payment (place order)
      Replace static data with: $cart->summary(), $order->payment_method
      Future: integrate real payment gateway (Stripe / PayMongo / GCash API)
    ==================================================================
--}}

@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-6xl mx-auto">

        <nav class="text-sm text-gray-400 mb-6">
            <a href="#" class="hover:text-gray-600">Home</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-700 font-medium">Payment</span>
        </nav>

        @include('pages.customer.cart.components.checkout-stepper', ['activeStep' => 'payment'])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            @include('pages.customer.payment.components.payment-details')

            <div class="space-y-6">
                @include('pages.customer.cart.components.voucher-card')
                @include('pages.customer.payment.components.order-summary')
            </div>

        </div>
    </div>
</div>

@include('pages.customer.payment.components.payment-scripts')

@endsection
