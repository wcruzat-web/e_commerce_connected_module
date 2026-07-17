{{-- ============================================================
    ERP SYSTEM
    E-Commerce Management System

    LAYOUT
    Admin Layout

    STATUS
    Frontend Development

    ============================================================

    PURPOSE

    Shared layout for admin pages.
    No customer header — sidebar + topbar are
    rendered within each page's content.

    ============================================================

    TODO: BACKEND

    Authentication Module
    - Session
    - Admin Guard

    Shared Components
    - Sidebar
    - Topbar

============================================================ --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ERP Admin')</title>

    {{-- ============================================================
        Outfit Font
    ============================================================ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">

    {{-- ============================================================
        Vite Assets
    ============================================================ --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body
    class="min-h-screen bg-[#F8F9FB] font-['Outfit'] antialiased">

    <main>

        @yield('content')

    </main>

    @stack('scripts')

</body>

</html>
