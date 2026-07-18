{{--
    ERP MODULE: Customer — Shop
    DESCRIPTION: Product listing page with data from ProductSeeder.
    DATA SOURCE: [OTHER MODULE] Procurement/Product Master
    NOTE: Products, categories, images, and specs are managed by Procurement.
          ECommerce module reads this data for display only.
--}}
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shop</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $products->count() }} products</p>
        </div>
    </div>

    {{-- Product grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group block">
                {{-- Image --}}
                <div class="bg-gray-50 h-48 flex items-center justify-center p-4 relative">
                    @if($product->badge)
                        <span class="absolute top-3 left-3 text-[10px] font-semibold uppercase tracking-wider px-2 py-1 rounded-full
                            @if($product->badge === 'Sale') bg-red-500 text-white
                            @elseif($product->badge === 'Best Seller') bg-amber-500 text-white
                            @else bg-blue-900 text-white
                            @endif">
                            {{ $product->badge }}
                        </span>
                    @endif
                    <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-24 h-24 object-contain">
                </div>
                {{-- Info --}}
                <div class="p-4">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">{{ $product->brand }}</p>
                    <h3 class="text-sm font-semibold text-gray-900 mt-0.5 leading-snug">{{ $product->name }}</h3>
                    {{-- Rating --}}
                    <div class="flex items-center gap-1 mt-1.5">
                        <div class="flex text-yellow-400 text-[11px]">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 {{ $i <= round($product->rating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 24 24">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[11px] text-gray-400">{{ $product->rating }} ({{ $product->review_count }})</span>
                    </div>
                    {{-- Price --}}
                    <div class="flex items-center gap-2 mt-2">
                        @if($product->sale_price)
                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

@endsection
