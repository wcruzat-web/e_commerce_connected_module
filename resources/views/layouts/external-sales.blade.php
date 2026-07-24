<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Customer Support — ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-indigo-900 text-white flex flex-col shrink-0">
            <div class="px-5 py-6 border-b border-indigo-800">
                <h1 class="text-lg font-bold tracking-tight">ERP SYSTEM</h1>
                <p class="text-xs text-indigo-300 mt-0.5">Sales & Customer Support</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ url('/external/sales') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-indigo-800 text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                    Fulfillment Queue
                </a>
                <a href="{{ url('/external/logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-200 hover:bg-indigo-800 hover:text-white transition-colors" target="_blank">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Webhook Logs
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-indigo-800">
                <p class="text-xs text-indigo-400">Connected via API</p>
                <p class="text-xs text-indigo-300 mt-0.5">Module: SALES</p>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">@yield('title', 'Sales & Customer Support')</h2>
                    <p class="text-xs text-gray-400 mt-0.5">ERP Integration Module</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
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
