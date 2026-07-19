{{-- CRUZAT — payment page: saved methods, card/GCash tabs, CVV, validation (ERPV0.2.5-0.2.12) --}}

@extends('layouts.store')

@php
    $defaultType = $defaultMethod?->payment_type ? strtolower($defaultMethod->payment_type) : 'visa';
    $defaultCardNumber = $defaultMethod && $defaultMethod->payment_type !== 'GCash' ? $defaultMethod->masked_account_number : '';
    $defaultExpiry = $defaultMethod && $defaultMethod->payment_type !== 'GCash' && $defaultMethod->expiry_date ? $defaultMethod->expiry_date->format('m/y') : '';
    $defaultGcashNumber = $defaultMethod && $defaultMethod->payment_type === 'GCash' ? preg_replace('/^\+63/', '', $defaultMethod->masked_account_number) : '';
    $defaultCvv = $defaultMethod && $defaultMethod->payment_type !== 'GCash' ? $defaultMethod->cvv : '';
@endphp

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
