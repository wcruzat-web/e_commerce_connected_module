@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-10 space-y-12">

    @include('pages.customer.shop.components.product-breadcrumb')
    @include('pages.customer.shop.components.product-hero')

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @include('pages.customer.shop.components.tab-navigation')

        <div class="p-6">
            @include('pages.customer.shop.components.specs-tab')
            @include('pages.customer.shop.components.compatibility-tab')
            @include('pages.customer.shop.components.reviews-section')
        </div>
    </div>
</div>

@include('pages.customer.shop.components.show-scripts')

@endsection
