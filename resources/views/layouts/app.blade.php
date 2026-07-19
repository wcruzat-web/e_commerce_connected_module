{{-- AGNER — app layout: copied from _new_auth, includes header for payment page (ERPV1.1) --}}
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
    <title>ShopEase</title>

    <script>window.__lang = '{{ $__lang === 'Filipino' ? 'fil' : 'en' }}'; window.__theme = '{{ $__theme }}';</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- CHANGES HERE: added your CRUZAT header for cart/checkout/payment/success/tracking pages --}}
    @include('components.header.header')

    @yield('content')

    @include('components.toast')

</body>
</html>
