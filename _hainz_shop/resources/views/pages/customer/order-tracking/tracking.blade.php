@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#f5f5f5] py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-5xl mx-auto space-y-5">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Tracking</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time updates on your ShopEase order.</p>
        </div>

        @include('pages.customer.order-tracking.components.track-another-order')

        @if(isset($order))
            @include('pages.customer.order-tracking.components.order-status-banner')
            @include('pages.customer.order-tracking.components.shipment-meta')
            @include('pages.customer.order-tracking.components.timeline')
            @include('pages.customer.order-tracking.components.shipment-items')
        @endif

        @include('pages.customer.order-tracking.components.support-shortcuts')

    </div>

    @include('pages.customer.order-tracking.components.chat-button')
</div>

@include('pages.customer.order-tracking.components.tracking-scripts')

@endsection
