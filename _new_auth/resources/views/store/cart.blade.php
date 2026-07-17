@extends('layouts.store')

@section('title', 'Cart')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

    @if(count($items))
        <div class="bg-white rounded-2xl shadow divide-y">
            @foreach($items as $id => $item)
                <div class="flex items-center gap-4 p-5" data-cart-row="{{ $id }}" data-unit-price="{{ $item['price'] }}">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                        class="w-16 h-16 object-cover rounded-lg"
                        onerror="this.onerror=null;this.src='https://picsum.photos/seed/p{{ $id }}/600/600';">
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-500">₱{{ number_format($item['price'], 2) }} each</p>
                    </div>

                    <form method="POST" action="{{ route('cart.update', $id) }}" class="js-cart-update flex items-center gap-2">
                        @csrf
                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="99"
                            class="w-16 border rounded-lg p-1 text-center">
                        <button type="submit" class="text-sky-500 text-sm hover:underline">Update</button>
                    </form>

                    <p class="font-bold w-28 text-right" data-line-total>
                        ₱{{ number_format($item['price'] * $item['qty'], 2) }}
                    </p>

                    <form method="POST" action="{{ route('cart.remove', $id) }}" class="js-cart-remove">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center mt-6">
            <span class="text-lg font-semibold">Total: ₱{{ number_format($total, 2) }}</span>
            <form method="POST" action="{{ route('orders.checkout') }}">
                @csrf
                <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5 hover-lift">
                    Checkout
                </button>
            </form>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
            <p class="text-lg">Your cart is empty.</p>
            <a href="{{ route('products') }}"
                class="inline-block mt-4 text-sky-500 font-semibold hover:underline">Browse products &rarr;</a>
        </div>
    @endif

</div>

@endsection
