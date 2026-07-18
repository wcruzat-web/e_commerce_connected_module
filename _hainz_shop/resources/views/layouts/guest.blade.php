{{--
    ERP MODULE: Authentication — Guest Layout
    LAYOUT: Guest
    DESCRIPTION: Minimal layout for auth pages (login, register, forgot-password). Has ShopEase branding header only. No navigation or mega menu.
    YIELDS: content, scripts
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#F8F9FB] font-['Outfit'] antialiased">
    <header class="py-3 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Shop<span class="text-[#00BBFF]">Ease</span>
            </h1>
        </div>
    </header>
    @yield('content')
    @stack('scripts')
</body>
</html>
