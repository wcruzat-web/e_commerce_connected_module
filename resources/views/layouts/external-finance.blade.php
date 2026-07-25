<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance & Accounting — ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: #f8f9fc; }

        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.875rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; color: #94a3b8; }
        .sidebar-link:hover { background: #1e293b; color: #f1f5f9; }
        .sidebar-link.active { background: #1e293b; color: #f1f5f9; }
        .sidebar-link svg { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }

        .kpi-card { background: white; border-radius: 1rem; padding: 1.25rem 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06); position: relative; overflow: hidden; }
        .kpi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .kpi-card.blue::before { background: #3b82f6; }
        .kpi-card.orange::before { background: #f59e0b; }
        .kpi-card.green::before { background: #10b981; }
        .kpi-card.purple::before { background: #8b5cf6; }
        .kpi-chip { width: 2.25rem; height: 2.25rem; border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-chip svg { width: 1.125rem; height: 1.125rem; }
        .kpi-label { font-size: 0.6875rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; }
        .kpi-value { font-size: 1.625rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .kpi-trend { font-size: 0.6875rem; font-weight: 600; }
        .kpi-trend.up { color: #10b981; }
        .kpi-trend.down { color: #ef4444; }
        .kpi-trend.neutral { color: #64748b; }

        .panel { background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06); overflow: hidden; }
        .panel-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; }
        .panel-header h3 { font-size: 0.8125rem; font-weight: 600; color: #0f172a; letter-spacing: 0.01em; }
        .panel-body { padding: 0.25rem; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #f1f5f9; }
        .data-table td { padding: 0.75rem 1rem; font-size: 0.8125rem; border-bottom: 1px solid #f8fafc; color: #475569; }
        .data-table tr:hover td { background: #f8fafc; }
        .data-table .selected td { background: #f0f9ff; }

        .badge { display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; }

        .progress-bar { height: 0.375rem; border-radius: 9999px; background: #f1f5f9; overflow: hidden; }
        .progress-bar-fill { height: 100%; border-radius: 9999px; transition: width 0.3s; }

        .detail-section { padding: 1.25rem; }
        .detail-label { font-size: 0.6875rem; color: #94a3b8; margin-bottom: 0.125rem; }
        .detail-value { font-size: 0.875rem; font-weight: 600; color: #0f172a; }
        .info-block { background: #f8fafc; border-radius: 0.75rem; padding: 0.875rem 1rem; }

        .status-dot { width: 0.5rem; height: 0.5rem; border-radius: 9999px; display: inline-block; flex-shrink: 0; }
    </style>
</head>
<body class="bg-[#f8f9fc]">
    <div class="flex min-h-screen">
        <aside class="w-60 bg-[#0f172a] text-white flex flex-col shrink-0">
            <div class="px-5 py-5 border-b border-[#1e293b]">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#3b82f6] flex items-center justify-center text-xs font-bold">E</div>
                    <div>
                        <h1 class="text-sm font-bold tracking-tight">ERP System</h1>
                        <p class="text-[10px] text-[#64748b] mt-px">Finance Module</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-3 py-3 space-y-0.5">
                <a href="{{ url('/external/finance') }}" class="sidebar-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                    Payment Processing
                </a>
            </nav>
            <div class="px-4 py-3 border-t border-[#1e293b]">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span class="text-[11px] text-[#64748b]">API Connected</span>
                </div>
                <p class="text-[10px] text-[#475569] mt-0.5">Module: FINANCE</p>
            </div>
        </aside>

        <div class="flex-1 min-w-0 flex flex-col">
            <header class="bg-white border-b border-[#f1f5f9] px-5 py-3 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#64748b]">ERP / Finance</span>
                    <span class="text-xs text-[#94a3b8]">/</span>
                    <span class="text-xs font-semibold text-[#0f172a]">@yield('title', 'Payment Processing')</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span class="text-[11px] text-[#64748b]">Online</span>
                </div>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>