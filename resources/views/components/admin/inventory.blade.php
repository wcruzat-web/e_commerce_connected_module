@props(['id' => 'page-inventory', 'class' => 'hidden'])

<div id="{{ $id }}" class="{{ $class }}">
  <div class="text-xs text-slate-400 mb-2">
    <span class="hover:text-slate-600 cursor-pointer">Admin</span> &nbsp;>&nbsp; <span class="text-slate-500">Inventory Monitoring</span>
  </div>

  <div class="flex items-start justify-between mb-5">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Inventory Monitoring <span class="font-semibold text-slate-500">Dashboard</span></h1>
      <p class="text-sm text-slate-400 mt-1">Last synchronized: <span id="lastSyncText">Dec 18, 2025</span></p>
    </div>
    <div class="flex items-center gap-2">
      <button id="exportReportBtn" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition">Export Report</button>
      <button id="forcedSyncBtn" class="px-4 py-2 text-sm font-medium bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition shadow-sm">Forced Sync</button>
    </div>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="text-xs font-medium text-slate-400 mb-2">TOTAL PRODUCT</div>
      <div class="text-2xl font-bold text-slate-900" id="statTotalProduct">—</div>
      <div class="text-xs mt-1 flex items-center gap-1" id="statTotalProductDiff">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
        <span id="statTotalProductDiffText">—</span>
      </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="text-xs font-medium text-slate-400 mb-2">AVAILABLE STOCK</div>
      <div class="text-2xl font-bold text-slate-900" id="statAvailableStock">—</div>
      <div class="text-xs mt-1 flex items-center gap-1" id="statAvailableStockDiff">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
        <span id="statAvailableStockDiffText">—</span>
      </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="text-xs font-medium text-slate-400 mb-2">LOW STOCK PRODUCT</div>
      <div class="text-2xl font-bold text-slate-900" id="statLowStock">—</div>
      <div class="text-xs mt-1 flex items-center gap-1" id="statLowStockDiff">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
        <span id="statLowStockDiffText">—</span>
      </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="text-xs font-medium text-slate-400 mb-2">OUT OF STOCK</div>
      <div class="text-2xl font-bold text-slate-900" id="statOutOfStock">—</div>
      <div class="text-xs mt-1 flex items-center gap-1" id="statOutOfStockDiff">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
        <span id="statOutOfStockDiffText">—</span>
      </div>
    </div>
  </div>

  <div class="flex gap-4 mb-5">
    <div class="flex-1 min-w-0 bg-white rounded-xl border border-gray-200 p-4">
      <div class="font-semibold text-slate-800 text-sm mb-0.5">Revenue Overview</div>
      <div class="text-xs text-slate-400 mb-3">Last 6 months performance</div>
      <div class="relative">
        <svg id="revenueChart" viewBox="0 0 600 160" class="w-full h-40"></svg>
        <div id="revenueTooltip" class="hidden absolute pointer-events-none bg-slate-900 text-white text-xs rounded-lg px-2.5 py-1.5 shadow-lg transition-opacity duration-100 whitespace-nowrap z-10">
          <div id="revenueTooltipMonth" class="font-medium"></div>
          <div id="revenueTooltipValue" class="text-sky-300"></div>
        </div>
      </div>
    </div>
    <div class="w-80 shrink-0 bg-white rounded-xl border border-gray-200 p-4">
      <div class="font-semibold text-slate-800 text-sm mb-0.5">Stock by Category</div>
      <div class="text-xs text-slate-400 mb-3">Current Inventory Distribution</div>
        <svg id="categoryChart" viewBox="0 0 320 160" class="w-full h-40"></svg>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-3">
        <div class="font-semibold text-slate-800 text-sm">Warehouse Sync Status</div>
        <span id="warehouseActiveCount" class="text-xs font-medium bg-emerald-100 text-emerald-600 px-2 py-1 rounded-full">—</span>
      </div>
      <div id="warehouseList" class="flex flex-col divide-y divide-gray-100"></div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 font-semibold text-slate-800 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          Low Stocks Alert
        </div>
        <span id="lowStockCount" class="text-xs font-medium bg-red-100 text-red-500 px-2 py-1 rounded-full">3 SKUs</span>
      </div>
      <div id="lowStockList" class="flex flex-col gap-3"></div>
    </div>
  </div>
</div>
