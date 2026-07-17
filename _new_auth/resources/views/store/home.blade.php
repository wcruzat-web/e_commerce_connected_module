@extends('layouts.store')

@section('title', 'Home')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">

    <!-- Hero -->
    <section class="bg-sky-600 text-white rounded-2xl p-10 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold leading-tight">Build your dream<br>setup with us.</h1>
            <p class="mt-4 max-w-md text-sky-100">
                Premium PC parts and components at competitive prices.
            </p>
            <a href="{{ route('products') }}"
                class="inline-block mt-6 bg-white text-sky-600 font-semibold px-6 py-3 rounded-lg hover:bg-sky-50">
                Shop Now
            </a>
        </div>
        <img src="https://picsum.photos/seed/setup/400/300" alt="PC setup"
            class="hidden md:block w-80 h-60 object-cover rounded-xl"
            onerror="this.onerror=null;this.src='https://picsum.photos/seed/pcsetup/600/450';">
    </section>

    <!-- Featured products -->
    <h2 class="text-2xl font-bold mt-12 mb-6">Featured Products</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($featured as $product)
            <a href="{{ route('product.show', $product['id']) }}"
                class="bg-white rounded-xl shadow p-4 hover:shadow-md transition block hover-lift animate-fade-up">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                    class="w-full h-40 object-cover rounded-lg"
                    onerror="this.onerror=null;this.src='https://picsum.photos/seed/p{{ $product['id'] }}/600/600';">
                <h3 class="font-semibold mt-3">{{ $product['name'] }}</h3>
                <p class="text-sky-600 font-bold mt-1">₱{{ number_format($product['price'], 2) }}</p>
            </a>
        @endforeach
    </div>

</div>

@endsection
