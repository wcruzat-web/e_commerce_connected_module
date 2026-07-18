{{-- ============================================================
    ERP SYSTEM
    E-Commerce Management System

    LAYOUT
    Master Application Layout

    STATUS
    Frontend Development

    ============================================================

    PURPOSE

    Shared layout used by all pages of the
    Real-Time Order Synchronization module.

    ============================================================

    TODO: BACKEND

    Authentication Module
    - Session
    - User Authentication

    Notification Module
    - Global Notifications

    Shared Components
    - Navbar
    - Footer

============================================================ --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ERP System')</title>

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

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')

</head>

<body
    class="min-h-screen bg-[#F8F9FB] font-['Outfit'] antialiased">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    @include('components.header.header')

    <main>

        @yield('content')

    </main>

    {{-- Future Footer --}}
    {{-- @include('components.footer.footer') --}}

    <div id="toastContainer" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none"></div>

    @stack('scripts')

</body>

</html>
