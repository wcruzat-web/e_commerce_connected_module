{{-- AGNER — customer layout: copied from _new_auth --}}
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

<title>ShopEase — @yield('title', 'My Account')</title>
<link rel="icon" type="image/png" href="{{ asset('shopease-logo.png') }}">

<script>window.__lang = '{{ $__lang === 'Filipino' ? 'fil' : 'en' }}'; window.__theme = '{{ $__theme }}';</script>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>


<body class="bg-gray-100">


<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    @include('components.sidebar')

    <!-- CONTENT -->
    <div class="flex-1 min-w-0">

        @include('components.topbar')

        <main class="p-4 sm:p-8">

            @yield('content')

        </main>

    </div>

</div>

@include('components.toast')
@include('components.confirm-modal')

</body>

</html>
