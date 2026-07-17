<!DOCTYPE html>
@php
    $__prefs = auth()->check()
        ? auth()->user()->settings()->pluck('setting_value', 'setting_key')->toArray()
        : [];
    $__theme = $__prefs['theme'] ?? 'Light';
    $__lang  = $__prefs['language'] ?? 'English';
@endphp

<html lang="{{ $__lang === 'Filipino' ? 'fil' : 'en' }}"
      class="{{ $__theme === 'Dark' ? 'dark' : '' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopEase — @yield('title', 'Store')</title>
<script>window.__lang = '{{ $__lang === 'Filipino' ? 'fil' : 'en' }}'; window.__theme = '{{ $__theme }}';</script>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-bold">Shop<span class="text-sky-500">Ease</span></a>

        <nav class="flex items-center gap-6 text-sm font-medium text-gray-700">
            <a href="{{ route('home') }}" class="hover:text-sky-500" data-i18n="store.home">Home</a>
            <a href="{{ route('products') }}" class="hover:text-sky-500" data-i18n="store.products">Products</a>
            <a href="{{ route('wishlist') }}" class="hover:text-sky-500 relative">
                <span data-i18n="store.wishlist">Wishlist</span>
                <span class="js-wishlist-badge absolute -top-2 -right-3 bg-sky-500 text-white text-xs rounded-full px-1.5 min-w-[18px] text-center {{ ($wishlistCount ?? 0) > 0 ? '' : 'hidden' }}">{{ $wishlistCount ?? 0 }}</span>
            </a>
            <a href="{{ route('cart') }}" class="hover:text-sky-500 relative">
                <span data-i18n="store.cart">Cart</span>
                <span class="js-cart-badge absolute -top-2 -right-3 bg-sky-500 text-white text-xs rounded-full px-1.5 min-w-[18px] text-center {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">{{ $cartCount ?? 0 }}</span>
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-sky-500" data-i18n="store.account">My Account</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline" data-i18n="store.logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-sky-500" data-i18n="store.login">Login</a>
                <a href="{{ route('register') }}" class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg" data-i18n="store.signup">Sign Up</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="bg-white border-t mt-12 py-6 text-center text-sm text-gray-500">
    ShopEase — dummy storefront (Inventory/Sales module placeholder).
</footer>

@include('components.toast')

</body>
</html>
