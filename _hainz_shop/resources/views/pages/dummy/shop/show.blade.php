{{--
    ERP MODULE: Customer — Shop
    PAGE: Product Details
    DESCRIPTION: Full product detail page with image, info, specs, compatibility, and reviews.
    DATA SOURCE: [OTHER MODULE] Procurement/Product Master
--}}
@extends('layouts.app')

@section('content')

@php
    $highlightSpecs = $product->specifications->where('is_highlight', true)->take(6);
    $groupedSpecs = $product->specifications->groupBy('group_name');
    $features = explode("\n", $product->description);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('products.index') }}" class="hover:text-cyan-500">Shop</a>
        <span>/</span>
        <span class="text-gray-600">{{ $product->category->name }}</span>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </div>

    {{-- Top section: Image + Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

        {{-- Left: Product Image --}}
        <div class="border border-gray-200 rounded-2xl p-8 flex items-center justify-center bg-white relative">
            @if($product->badge)
                <span class="absolute top-4 left-4 text-[11px] font-semibold uppercase tracking-wider px-3 py-1 rounded-full z-10
                    @if($product->badge === 'Sale') bg-red-500 text-white
                    @elseif($product->badge === 'Best Seller') bg-amber-500 text-white
                    @else bg-blue-900 text-white
                    @endif">
                    {{ $product->badge }}
                </span>
            @endif
            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-64 h-64 object-contain">
        </div>

        {{-- Right: Product Info --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ $product->brand }}</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="flex items-center gap-2 mt-2">
                <div class="flex text-yellow-400 gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $i <= round($product->rating) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-sm font-medium text-gray-900">{{ $product->rating }}</span>
                <span class="text-sm text-gray-400">({{ $product->review_count }} reviews)</span>
            </div>

            {{-- Price --}}
            <div class="flex items-center gap-3 mt-4">
                @if($product->sale_price)
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-xl text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    <span class="text-[11px] font-semibold bg-red-500 text-white px-2 py-0.5 rounded-full">SALE</span>
                @else
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <hr class="my-5 border-gray-200">

            {{-- At a Glance --}}
            @if($highlightSpecs->count())
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">At a Glance</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($highlightSpecs as $spec)
                        <div class="border border-gray-200 rounded-lg px-3 py-2">
                            <p class="text-[11px] text-gray-400">{{ $spec->label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $spec->value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Quantity + Add to Cart — posts to CartController@add --}}
            <form method="POST" action="{{ route('cart.add') }}" class="flex items-center gap-4 mt-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" class="qty-input" value="1">
                <div class="flex items-center border border-gray-200 rounded-lg">
                    <button type="button" onclick="const el=this.parentNode.querySelector('.qty'),inp=this.parentNode.querySelector('.qty-input');let v=parseInt(el.textContent)-1;if(v<1)v=1;el.textContent=v;inp.value=v" class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">−</button>
                    <span class="qty w-10 text-center text-sm font-medium text-gray-900">1</span>
                    <button type="button" onclick="const el=this.parentNode.querySelector('.qty'),inp=this.parentNode.querySelector('.qty-input');let v=parseInt(el.textContent)+1;el.textContent=v;inp.value=v" class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">+</button>
                </div>
                {{-- ToDo: Add wishlist toggle (heart icon) — posts to wishlist route when built --}}
                <button type="submit" class="flex-1 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-6 py-2.5 rounded-lg">Add to Cart</button>
                <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
            </form>

            {{-- Features --}}
            <div class="mt-5 space-y-1.5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Features</p>
                @foreach(['ICE Cooling Solution', 'Advanced Power Delivery', 'AI Powered Graphics'] as $feature)
                    <div class="flex items-center gap-2 text-xs text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ $feature }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom section: Specs Tabs --}}
    <div class="border-t border-gray-200 pt-8">
        {{-- Tabs --}}
        <div class="flex items-center gap-6 border-b border-gray-200">
            <button type="button" class="pb-3 text-sm font-semibold text-cyan-500 border-b-2 border-cyan-500">Full Specifications</button>
            <button type="button" class="pb-3 text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">Compatibility</button>
            <button type="button" class="pb-3 text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">Reviews</button>
        </div>

        {{-- Spec Tables --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mt-6">
            @foreach ($groupedSpecs as $groupName => $specs)
                <div>
                    <h3 class="text-xs font-bold text-cyan-500 uppercase tracking-wider mb-2">{{ $groupName }}</h3>
                    <div class="divide-y divide-gray-100 border border-gray-200 rounded-xl overflow-hidden">
                        @foreach ($specs as $spec)
                            <div class="flex items-center justify-between px-4 py-2.5 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <span class="text-xs text-gray-500">{{ $spec->label }}</span>
                                <span class="text-xs font-medium text-gray-900 text-right">{{ $spec->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
