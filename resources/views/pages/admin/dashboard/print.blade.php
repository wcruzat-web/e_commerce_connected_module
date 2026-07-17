@php
    $maxRevenue = max(array_column($revenueMonths, 'revenue')) ?: 1;
    $startX = 70;
    $spacing = 84;
    $catColors = ['bg-blue-900', 'bg-cyan-500', 'bg-sky-300', 'bg-purple-400', 'bg-amber-500', 'bg-green-500'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShopEase Report - {{ now()->format('F d, Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', Arial, sans-serif; color: #111; padding: 40px; background: #fff; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        .sub { color: #888; font-size: 14px; margin-bottom: 30px; }
        h2 { font-size: 16px; margin-bottom: 12px; color: #333; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 30px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
        .card .label { font-size: 12px; color: #888; margin-bottom: 4px; }
        .card .value { font-size: 22px; font-weight: 700; }
        .grid-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px; }
        .section { border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 8px 4px; border-bottom: 2px solid #e5e7eb; color: #888; font-weight: 600; }
        td { padding: 10px 4px; border-bottom: 1px solid #f3f4f6; }
        .bar { height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden; margin-top: 4px; }
        .bar-fill { height: 100%; border-radius: 4px; }
        .badge { font-size: 11px; padding: 2px 10px; border-radius: 10px; font-weight: 600; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-amber { background: #fef3c7; color: #d97706; }
        .badge-green { background: #d1fae5; color: #059669; }
        .badge-gray { background: #f3f4f6; color: #6b7280; }
        .flex { display: flex; }
        .between { justify-content: space-between; }
        .items-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .gap-4 { gap: 16px; }
        .mt-2 { margin-top: 8px; }
        .mb-1 { margin-bottom: 4px; }
        .text-sm { font-size: 13px; }
        .text-xs { font-size: 11px; color: #888; }
        .font-medium { font-weight: 500; }
        .font-bold { font-weight: 700; }
        .text-right { text-align: right; }
        .w-full { width: 100%; }
        .shrink-0 { flex-shrink: 0; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pill { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
        .print-btn { margin-bottom: 20px; padding: 10px 24px; background: #06b6d4; color: #fff; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; }
        .print-btn:hover { background: #0891b2; }
        .chart-tooltip { position: fixed; background: #1e293b; color: #fff; font-size: 12px; padding: 8px 14px; border-radius: 8px; pointer-events: none; z-index: 9999; font-family: Outfit, Arial, sans-serif; box-shadow: 0 4px 12px rgba(0,0,0,0.15); line-height: 1.4; display: none; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Print Report</button>
    <h1>ShopEase Admin Report</h1>
    <p class="sub">{{ now()->format('F d, Y') }}</p>

    <div class="grid-4">
        <div class="card">
            <div class="label">Total Revenue</div>
            <div class="value">₱{{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Orders This Month</div>
            <div class="value">{{ $stats['orders_this_month'] }}</div>
        </div>
        <div class="card">
            <div class="label">Total Orders</div>
            <div class="value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="card">
            <div class="label">Low Stock Alerts</div>
            <div class="value">{{ $stats['low_stock_count'] }} SKUs</div>
        </div>
    </div>

    <div class="grid-3">
        <div class="section">
            <h2>Revenue Overview</h2>
            <svg viewBox="0 0 560 200" class="w-full" style="height:180px">
                <g stroke="#f1f5f9" stroke-width="1">
                    <line x1="50" y1="10" x2="550" y2="10"></line>
                    <line x1="50" y1="52" x2="550" y2="52"></line>
                    <line x1="50" y1="94" x2="550" y2="94"></line>
                    <line x1="50" y1="136" x2="550" y2="136"></line>
                    <line x1="50" y1="178" x2="550" y2="178"></line>
                </g>
                <g fill="#999" font-size="10" text-anchor="end">
                    <text x="44" y="14">₱{{ number_format($maxRevenue) }}</text>
                    <text x="44" y="56">₱{{ number_format($maxRevenue * 0.75) }}</text>
                    <text x="44" y="98">₱{{ number_format($maxRevenue * 0.5) }}</text>
                    <text x="44" y="140">₱{{ number_format($maxRevenue * 0.25) }}</text>
                    <text x="44" y="182">₱0</text>
                </g>
                @php $pts = []; foreach ($revenueMonths as $i => $m) { $x = $startX + ($i * $spacing); $y = 178 - (($m['revenue'] / $maxRevenue) * 168); $pts[] = "$x,$y"; } @endphp
                <polyline points="{{ implode(' ', $pts) }}" fill="none" stroke="#06b6d4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                <g fill="#06b6d4">
                    @foreach ($revenueMonths as $i => $m)
                        @php $x = $startX + ($i * $spacing); $y = 178 - (($m['revenue'] / $maxRevenue) * 168); @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" style="cursor:pointer"
                            data-tooltip="{{ $m['month'] }}|₱{{ number_format($m['revenue'], 2) }}"
                            onmouseenter="showChartTooltip(event, this)" onmousemove="moveChartTooltip(event)" onmouseleave="hideChartTooltip()">
                        </circle>
                    @endforeach
                </g>
                <g fill="#999" font-size="10" text-anchor="middle">
                    @foreach ($revenueMonths as $i => $m)
                        <text x="{{ $startX + ($i * $spacing) }}" y="196">{{ $m['month'] }}</text>
                    @endforeach
                </g>
            </svg>
        </div>
        <div class="section">
            <h2>Revenue by Category</h2>
            @forelse ($revenueByCategory as $i => $cat)
                <div class="mb-1">
                    <div class="flex between items-center text-sm">
                        <span>{{ $cat['label'] }}</span>
                        <span class="font-medium">₱{{ number_format($cat['amount']) }} <span class="text-xs">({{ $cat['pct'] }}%)</span></span>
                    </div>
                    <div class="bar"><div class="bar-fill {{ $catColors[$i % count($catColors)] }}" style="width:{{ $cat['pct'] * 2.2 }}%"></div></div>
                </div>
            @empty
                <p class="text-sm" style="color:#888">No data</p>
            @endforelse
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:30px;">
        <div class="section">
            <h2>Low Stock Alerts</h2>
            <table>
                <tr><th>Product</th><th>SKU</th><th class="text-right">Left</th></tr>
                @forelse ($lowStockProducts as $p)
                    <tr><td class="font-medium">{{ $p['name'] }}</td><td class="text-xs">{{ $p['sku'] }}</td><td class="text-right font-bold" style="color:#dc2626">{{ $p['left'] }}</td></tr>
                @empty
                    <tr><td colspan="3" style="color:#888">None</td></tr>
                @endforelse
            </table>
        </div>
        <div class="section">
            <h2>Recent Orders</h2>
            <table>
                <tr><th>Customer</th><th>Items</th><th class="text-right">Total</th><th>Status</th></tr>
                @forelse ($recentOrders as $o)
                    <tr>
                        <td class="font-medium">{{ $o['name'] }}</td>
                        <td class="text-xs truncate" style="max-width:120px">{{ $o['spec'] }}</td>
                        <td class="text-right font-bold">₱{{ number_format($o['price'], 2) }}</td>
                        <td><span class="badge @if($o['status']==='shipped') badge-blue @elseif($o['status']==='processing') badge-amber @elseif($o['status']==='delivered') badge-green @else badge-gray @endif">{{ ucfirst($o['status']) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="color:#888">No orders</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Monthly Revenue Breakdown</h2>
        <table>
            <tr><th>Month</th><th class="text-right">Revenue</th></tr>
            @foreach ($revenueMonths as $m)
                <tr><td>{{ $m['month'] }}</td><td class="text-right font-bold">₱{{ number_format($m['revenue'], 2) }}</td></tr>
            @endforeach
        </table>
    </div>
<div id="chartTooltip" class="chart-tooltip"></div>
<script>
    function showChartTooltip(e, el) {
        var tip = document.getElementById('chartTooltip');
        if (!tip) return;
        var parts = el.getAttribute('data-tooltip').split('|');
        tip.innerHTML = '<div style="font-weight:600;margin-bottom:2px">' + parts[0] + '</div><div style="color:#06b6d4;font-weight:700">' + parts[1] + '</div>';
        tip.style.display = 'block';
        moveChartTooltip(e);
    }
    function moveChartTooltip(e) {
        var tip = document.getElementById('chartTooltip');
        if (!tip) return;
        var x = e.clientX + 14, y = e.clientY - 10;
        if (x + 140 > window.innerWidth) x = e.clientX - 140;
        if (y < 0) y = e.clientY + 20;
        tip.style.left = x + 'px';
        tip.style.top = y + 'px';
    }
    function hideChartTooltip() {
        var tip = document.getElementById('chartTooltip');
        if (tip) tip.style.display = 'none';
    }
</script>
</body>
</html>
