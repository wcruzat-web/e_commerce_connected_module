{{-- CRUZAT — checkout page: contact fields, address, payment method selection (ERPV0.2) --}}

@extends('layouts.store')

@section('content')

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-6xl mx-auto">

        <nav class="text-sm text-gray-400 mb-6">
            <a href="{{ route('cart') }}" class="hover:text-gray-600">Cart</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-700 font-medium">Checkout</span>
        </nav>

        @include('pages.customer.cart.components.checkout-stepper', ['activeStep' => 'checkout'])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @include('pages.customer.checkout.components.checkout-details')

            <div class="space-y-6">
                @include('pages.customer.cart.components.voucher-card')
                @include('pages.customer.checkout.components.order-summary')
            </div>
        </div>
    </div>
</div>

@include('pages.customer.checkout.components.checkout-scripts')

@endsection
