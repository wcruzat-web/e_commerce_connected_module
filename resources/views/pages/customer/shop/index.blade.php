@extends('layouts.app')

@section('content')

@include('pages.customer.shop.components.hero-banner')

<main class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        @include('pages.customer.shop.components.filters')

        <div class="lg:col-span-3">
            @include('pages.customer.shop.components.toolbar')

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    @include('pages.customer.shop.components.product-card')
                @endforeach
            </div>
        </div>
    </div>
</main>

@include('pages.customer.shop.components.index-scripts')
@include('pages.customer.shop.components.index-footer')

@endsection
