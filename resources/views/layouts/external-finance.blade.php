<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance & Accounting — ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-emerald-900 text-white flex flex-col shrink-0">
            <div class="px-5 py-6 border-b border-emerald-800">
                <h1 class="text-lg font-bold tracking-tight">ERP SYSTEM</h1>
                <p class="text-xs text-emerald-300 mt-0.5">Finance & Accounting</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ url('/external/finance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-emerald-800 text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                    Payment Processing
                </a>
                <a href="{{ url('/external/logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-emerald-200 hover:bg-emerald-800 hover:text-white transition-colors" target="_blank">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Webhook Logs
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-emerald-800">
                <p class="text-xs text-emerald-400">Connected via API</p>
                <p class="text-xs text-emerald-300 mt-0.5">Module: FINANCE</p>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">@yield('title', 'Finance & Accounting')</h2>
                    <p class="text-xs text-gray-400 mt-0.5">ERP Integration Module</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-xs text-gray-500">Web Service Active</span>
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
