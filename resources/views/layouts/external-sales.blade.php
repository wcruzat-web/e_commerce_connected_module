<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Fulfillment — ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.875rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; color: #94a3b8; }
        .sidebar-link:hover { background: #1e293b; color: #f1f5f9; }
        .sidebar-link.active { background: #1e293b; color: #f1f5f9; }
        .sidebar-link svg { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 0.625rem 0.875rem; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .data-table td { padding: 0.625rem 0.875rem; font-size: 0.8125rem; border-bottom: 1px solid #f1f5f9; }
        .data-table tr:hover td { background: #f8fafc; }
        .data-table .selected td { background: #eff6ff; }
        .badge { display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem 1.25rem; }
        .stat-label { font-size: 0.75rem; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-top: 0.25rem; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; }
        .card-header { padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; }
        .card-header h3 { font-size: 0.8125rem; font-weight: 600; color: #0f172a; }
        .card-body { padding: 0.5rem; }
    </style>
</head>
<body class="bg-[#f1f5f9]">
    <div class="flex min-h-screen">
        <aside class="w-60 bg-[#0f172a] text-white flex flex-col shrink-0">
            <div class="px-5 py-5 border-b border-[#1e293b]">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded bg-[#8b5cf6] flex items-center justify-center text-xs font-bold">E</div>
                    <div>
                        <h1 class="text-sm font-bold tracking-tight">ERP System</h1>
                        <p class="text-[10px] text-[#64748b] mt-px">Sales Module</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-3 py-3 space-y-0.5">
                <a href="{{ url('/external/sales') }}" class="sidebar-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                    Fulfillment Queue
                </a>

            </nav>
            <div class="px-4 py-3 border-t border-[#1e293b]">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span class="text-[11px] text-[#64748b]">API Connected</span>
                </div>
                <p class="text-[10px] text-[#475569] mt-0.5">Module: SALES</p>
            </div>
        </aside>

        <div class="flex-1 min-w-0 flex flex-col">
            <header class="bg-white border-b border-[#e2e8f0] px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#64748b]">ERP / Sales</span>
                    <span class="text-xs text-[#94a3b8]">/</span>
                    <span class="text-xs font-semibold text-[#0f172a]">@yield('title', 'Fulfillment Queue')</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span class="text-[11px] text-[#64748b]">Online</span>
                </div>
            </header>
            <main class="flex-1 p-5">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
