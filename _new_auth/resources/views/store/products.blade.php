@extends('layouts.store')

@section('title', 'Products')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6">All Products</h1>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 stagger">
        @foreach($products as $product)
            @php($inWish = in_array($product['id'], $wishlistIds))
            <div class="bg-white rounded-xl shadow p-4 hover:shadow-md transition flex flex-col hover-lift animate-fade-up">
                <a href="{{ route('product.show', $product['id']) }}" class="block">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                        class="w-full h-40 object-cover rounded-lg"
                        onerror="this.onerror=null;this.src='https://picsum.photos/seed/p{{ $product['id'] }}/600/600';">
                    <p class="text-xs text-gray-400 mt-3">{{ $product['category'] }}</p>
                    <h3 class="font-semibold">{{ $product['name'] }}</h3>
                    <p class="text-sky-600 font-bold mt-1">₱{{ number_format($product['price'], 2) }}</p>
                </a>

                <div class="mt-3 flex gap-2">
                    <form method="POST" action="{{ route('cart.add') }}" class="js-add-cart flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit"
                            class="w-full bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition hover:-translate-y-0.5">
                            Add to Cart
                        </button>
                    </form>

                    <form method="POST" action="{{ route('wishlist.toggle') }}" class="js-wish-toggle-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <button type="submit" title="{{ $inWish ? 'Remove from wishlist' : 'Add to Wishlist' }}"
                            class="border rounded-lg p-2 transition hover:bg-sky-50 js-wish-heart {{ $inWish ? 'text-red-500 border-red-500' : 'text-sky-500 border-sky-500' }}"
                            data-in-wishlist="{{ $inWish ? '1' : '0' }}">
                            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/heart.svg"
                                class="w-4 h-4 {{ $inWish ? 'fill-red-500 text-red-500' : '' }}" alt="Wishlist">
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection
