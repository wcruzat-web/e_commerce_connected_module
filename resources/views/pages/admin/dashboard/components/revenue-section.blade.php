{{-- CRUZAT — revenue-section: 6-month revenue chart with gradient fill --}}{{--
    ERP MODULE: Admin Dashboard
    COMPONENT: Revenue Section
    DESCRIPTION: Revenue overview line chart + revenue by category breakdown.
    TODO: Replace with $revenueSeries / $revenueByCategory from DashboardController
--}}

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900">Revenue Overview</h2>
        <p class="text-xs text-gray-400 mb-4">Last 6 months performance</p>

        @php
            $maxRevenue = max(array_column($revenueMonths, 'revenue')) ?: 1;
            $chartHeight = 162;
            $chartWidth = 500;
            $stepY = 38;
        @endphp

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 200" class="w-full h-48">
            <g stroke="#f1f5f9" stroke-width="1">
                <line x1="50" y1="10" x2="550" y2="10"></line>
                <line x1="50" y1="48" x2="550" y2="48"></line>
                <line x1="50" y1="86" x2="550" y2="86"></line>
                <line x1="50" y1="124" x2="550" y2="124"></line>
                <line x1="50" y1="162" x2="550" y2="162"></line>
            </g>
            <g class="fill-gray-400" font-size="10" text-anchor="end">
                <text x="44" y="14">₱{{ number_format($maxRevenue) }}</text>
                <text x="44" y="52">₱{{ number_format($maxRevenue * 0.75) }}</text>
                <text x="44" y="90">₱{{ number_format($maxRevenue * 0.5) }}</text>
                <text x="44" y="128">₱{{ number_format($maxRevenue * 0.25) }}</text>
                <text x="44" y="166">₱0</text>
            </g>
            @php
                $points = [];
                $spacing = 84;
                $startX = 70;
                foreach ($revenueMonths as $i => $month) {
                    $x = $startX + ($i * $spacing);
                    $yRatio = $month['revenue'] / $maxRevenue;
                    $y = 162 - ($yRatio * 152);
                    $points[] = "$x,$y";
                }
                $polyline = implode(' ', $points);
            @endphp
            <polyline points="{{ $polyline }}" fill="none" stroke="#06b6d4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
            <g fill="#06b6d4">
                @foreach ($revenueMonths as $i => $month)
                    @php
                        $x = $startX + ($i * $spacing);
                        $yRatio = $month['revenue'] / $maxRevenue;
                        $y = 162 - ($yRatio * 152);
                    @endphp
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="5" style="cursor:pointer"
                        data-tooltip="{{ $month['month'] }}|₱{{ number_format($month['revenue'], 2) }}"
                        onmouseenter="showChartTooltip(event, this)" onmousemove="moveChartTooltip(event)" onmouseleave="hideChartTooltip()">
                    </circle>
                @endforeach
            </g>
            <g class="fill-gray-400" font-size="10" text-anchor="middle">
                @foreach ($revenueMonths as $i => $month)
                    <text x="{{ $startX + ($i * $spacing) }}" y="186">{{ $month['month'] }}</text>
                @endforeach
            </g>
        </svg>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900">Revenue by Category</h2>
        <p class="text-xs text-gray-400 mb-4">{{ now()->format('F Y') }}</p>

        @php
            $catColors = ['bg-blue-900', 'bg-cyan-500', 'bg-sky-300', 'bg-purple-400', 'bg-amber-500', 'bg-green-500'];
        @endphp

        <div class="space-y-4">
            @forelse ($revenueByCategory as $i => $cat)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-600">{{ $cat['label'] }}</span>
                        <span class="text-gray-900 font-medium">₱{{ number_format($cat['amount']) }} <span class="text-gray-400">{{ $cat['pct'] }}%</span></span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $catColors[$i % count($catColors)] }}" style="width: {{ $cat['pct'] * 2.2 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">No revenue data yet.</p>
            @endforelse
        </div>
    </div>
</div>
