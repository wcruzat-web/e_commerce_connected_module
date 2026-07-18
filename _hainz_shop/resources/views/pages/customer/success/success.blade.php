{{--
    ERP MODULE: Checkout — Order Confirmation (Success Page)
    DESCRIPTION: Displays confirmed order details (order number, total, items) after successful payment.
    DATA SOURCE: $order fetched by session order_id from route /success
    ROUTE: GET /success
--}}

@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-6xl mx-auto">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-400 mb-6">
            <a href="#" class="hover:text-gray-600">Home</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-700 font-medium">Success</span>
        </nav>

        {{-- Checkout Stepper (active: success) --}}
        @include('pages.customer.cart.components.checkout-stepper', ['activeStep' => 'success'])

        {{-- Main Grid: Order Confirmed (left) + Summary (right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Order Confirmed --}}
            @include('pages.customer.success.components.order-confirmed')

            {{-- Sidebar (no voucher — order is already placed) --}}
            <div class="space-y-6">
                @include('pages.customer.success.components.order-summary')
            </div>

        </div>
    </div>
</div>

{{-- Frontend-only JavaScript --}}
@include('pages.customer.success.components.success-scripts')

@endsection
