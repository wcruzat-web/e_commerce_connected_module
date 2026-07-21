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

<script>
function pollNotifications() {
    fetch('{{ route('admin.dashboard.notifications') }}')
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var dropdown = document.getElementById('notificationsDropdown');
            if (!dropdown) return;
            var header = dropdown.querySelector('.border-b');
            if (header) {
                var badge = header.querySelector('.rounded-full');
                if (badge) badge.textContent = data.newCount + ' new';
            }
            var list = dropdown.querySelector('.max-h-64');
            if (list && data.recent) {
                list.innerHTML = '';
                data.recent.forEach(function (n) {
                    var div = document.createElement('div');
                    div.className = 'flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors';
                    div.innerHTML = (n.iconHtml || '') + '<div class="flex-1 min-w-0"><p class="text-xs text-gray-700">' + n.title + '</p><p class="text-[11px] text-gray-400 mt-0.5">' + n.time + '</p></div>';
                    list.appendChild(div);
                });
            }
            var bell = document.querySelector('button[aria-label="Notifications"]');
            if (bell) {
                var dot = bell.querySelector('.bg-red-500');
                if (data.newCount > 0) {
                    if (!dot) {
                        dot = document.createElement('span');
                        dot.className = 'absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500';
                        bell.appendChild(dot);
                    }
                } else if (dot) {
                    dot.remove();
                }
            }
        })
        .catch(function () {});
}
setInterval(pollNotifications, 30000);
</script>

</body>

</html>
