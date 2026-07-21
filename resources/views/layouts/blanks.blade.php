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
<title>ShopEase - @yield('title', 'ShopEase')</title>
<link rel="icon" type="image/png" href="{{ asset('shopease-logo.png') }}">
<script>window.__lang = '{{ $__lang === 'Filipino' ? 'fil' : 'en' }}'; window.__theme = '{{ $__theme }}';</script>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <main class="min-h-screen p-6 md:p-10">
        @yield('content')
    </main>

    <div id="toastContainer" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none"></div>

</body>
</html>
