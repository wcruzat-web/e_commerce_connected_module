{{-- AGNER — store layout: includes header (ERPV0.2) --}}
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

{{-- CHANGES HERE: replaced inline store header with your CRUZAT header component --}}
@include('components.header.header')

<main>
    @yield('content')
</main>

<footer class="bg-white border-t mt-12 py-6 text-center text-sm text-gray-500">
    ShopEase — dummy storefront (Inventory/Sales module placeholder).
</footer>

@include('components.toast')

</body>
</html>
