{{--
    ERP MODULE: Shopping Cart (Cart Page)
    PAGE: Cart
    DESCRIPTION: Displays cart items, quantity controls, and order summary.
    DATA SOURCE: CartController@index — $cart (Cart model loaded via CartRepository), $summary (CartSummaryDTO)
    ROUTES:
      GET    /cart                 — CartController@index
      PATCH  /cart/{cartItem}      — CartController@updateQuantity  (ToDo: wire via AJAX)
      DELETE /cart/{cartItem}      — CartController@remove          (form submit)
      POST   /cart/voucher         — ToDo: create when coupon system is built
--}}

@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-6xl mx-auto">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-400 mb-6">
            <a href="#" class="hover:text-gray-600">Home</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-700 font-medium">Shopping Cart</span>
        </nav>

        {{-- Checkout Stepper --}}
        @include('pages.customer.cart.components.checkout-stepper')

        {{-- Main Grid: Cart Items (left) + Summary (right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Cart Items --}}
            @include('pages.customer.cart.components.cart-items-list')

            {{-- Sidebar --}}
            <div class="space-y-6">
                @include('pages.customer.cart.components.voucher-card')
                @include('pages.customer.cart.components.order-summary')
            </div>

        </div>
    </div>
</div>

{{-- Frontend-only JavaScript --}}
@include('pages.customer.cart.components.cart-scripts')

@endsection
