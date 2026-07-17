@extends('layouts.store')

@section('title', $product['name'])

@section('content')

@php($inWish = in_array($product['id'], $wishlistIds))

<div class="max-w-6xl mx-auto px-6 py-10">

    <a href="{{ route('products') }}" class="text-sky-500 text-sm hover:underline">&larr; Back to products</a>

    <div class="bg-white rounded-2xl shadow p-8 mt-4 grid md:grid-cols-2 gap-8 animate-fade-up">
        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
            class="w-full h-80 object-cover rounded-xl"
            onerror="this.onerror=null;this.src='https://picsum.photos/seed/p{{ $product['id'] }}/600/600';">

        <div>
            <p class="text-xs text-gray-400">{{ $product['category'] }}</p>
            <h1 class="text-3xl font-bold mt-1">{{ $product['name'] }}</h1>
            <p class="text-sky-600 text-2xl font-bold mt-2">₱{{ number_format($product['price'], 2) }}</p>
            <p class="text-gray-600 mt-4">{{ $product['description'] }}</p>

            <form method="POST" action="{{ route('cart.add') }}" class="js-add-cart flex items-end gap-3 mt-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <div>
                    <label class="text-sm text-gray-600">Qty</label>
                    <input type="number" name="qty" value="1" min="1" max="99"
                        class="w-20 border rounded-lg p-2 mt-1">
                </div>
                <button type="submit"
                    class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
                        class="w-4 h-4" alt=""> Add to Cart
                </button>
            </form>

            <form method="POST" action="{{ route('wishlist.toggle') }}" class="js-wish-toggle-form mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 border js-wish-heart {{ $inWish ? 'text-red-500 border-red-500' : 'border-sky-500 text-sky-500' }} hover:bg-sky-50 px-6 py-3 rounded-lg font-semibold transition"
                    data-in-wishlist="{{ $inWish ? '1' : '0' }}"
                    title="{{ $inWish ? 'Remove from wishlist' : 'Add to Wishlist' }}">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/heart.svg"
                        class="w-4 h-4 {{ $inWish ? 'fill-red-500 text-red-500' : '' }}" alt="">
                    <span>{{ $inWish ? 'Remove from Wishlist' : 'Add to Wishlist' }}</span>
                </button>
            </form>

            <a href="{{ route('cart') }}"
                class="block text-center border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-semibold mt-3">
                View Cart
            </a>
        </div>
    </div>

</div>

@endsection
