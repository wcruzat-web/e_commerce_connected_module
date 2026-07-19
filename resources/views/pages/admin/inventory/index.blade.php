@extends('layouts.admin')

@section('title', 'Inventory Monitoring')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">

    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')

        <div class="p-4 lg:p-6">
            <x-admin.inventory class="" />
        </div>
    </div>
</div>

<div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 z-50"></div>

<style>
  .fade-in { animation: fadeIn .15s ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px);} to { opacity: 1; transform: translateY(0);} }
</style>

<script>
/* ---------------------------------------------------------
   API HELPERS
   ESTEBAN — adapted: API_BASE='/api/admin' (was '')
-------------------------------------------------------- */
const API_BASE = '/api/admin';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function apiHeaders(isJson = true) {
  const h = { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' };
  if (isJson) h['Accept'] = 'application/json';
  return h;
}

async function apiGet(url) {
  const res = await fetch(API_BASE + url, { headers: apiHeaders() });
  return res.json();
}

async function apiPost(url, formData) {
  const res = await fetch(API_BASE + url, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    body: formData,
  });
  return res.json();
}

function showToast(message) {
  const toast = document.getElementById("toast");
  if (!toast) return;
  toast.textContent = message;
  toast.classList.remove("opacity-0", "pointer-events-none");
  clearTimeout(window._toastTimer);
  window._toastTimer = setTimeout(() => {
    toast.classList.add("opacity-0", "pointer-events-none");
  }, 2200);
}

/* ---------------------------------------------------------
   INVENTORY
   ESTEBAN — adapted: all chart/table rendering from original layout JS
-------------------------------------------------------- */
function renderDiff(elId, diff) {
  const el = document.getElementById(elId);
  if (!el) return;
  if (diff.direction === 'up') {
    el.className = 'text-xs text-emerald-500 font-medium mt-1 flex items-center gap-1';
    el.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> ${diff.text}`;
  } else if (diff.direction === 'down') {
    el.className = 'text-xs text-red-500 font-medium mt-1 flex items-center gap-1';
    el.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg> ${diff.text}`;
  } else {
    el.className = 'text-xs text-slate-400 font-medium mt-1 flex items-center gap-1';
    el.innerHTML = diff.text;
  }
}

async function renderInventoryStats() {
  const data = await apiGet('/inventory/stats');
  document.getElementById("statTotalProduct").textContent = data.totalProduct.toLocaleString();
  renderDiff("statTotalProductDiff", data.totalProductDiff);
  document.getElementById("statAvailableStock").textContent = data.availableStock.toLocaleString();
  renderDiff("statAvailableStockDiff", data.stockDiff);
  document.getElementById("statLowStock").textContent = data.lowStockProduct;
  renderDiff("statLowStockDiff", data.lowStockDiff);
  document.getElementById("statOutOfStock").textContent = data.outOfStock;
  renderDiff("statOutOfStockDiff", data.outOfStockDiff);

  window._categoryStockData = data.categoryStock;
  window._lowStockAlerts = data.lowStockAlerts;
  renderCategoryChart();
  renderLowStockList();
}

async function renderRevenueChart() {
  const revenueData = await apiGet('/revenue');
  window._revenueData = revenueData;

  const svg = document.getElementById("revenueChart");
  if (!revenueData || revenueData.length === 0) {
    svg.innerHTML = '<text x="300" y="85" text-anchor="middle" fill="#94a3b8" font-size="13" font-family="Outfit,sans-serif">No revenue data yet</text>';
    return;
  }

  const tooltip = document.getElementById("revenueTooltip");
  const tooltipMonth = document.getElementById("revenueTooltipMonth");
  const tooltipValue = document.getElementById("revenueTooltipValue");
  const W = 600, H = 160, padL = 45, padB = 25, padT = 10, padR = 15;
  const chartW = W - padL - padR, chartH = H - padT - padB;
  const maxVal = Math.max(...revenueData.map(d => d.value)) * 1.15;

  const points = revenueData.map((d, i) => {
    const x = padL + (i / (revenueData.length - 1)) * chartW;
    const y = padT + chartH - (d.value / maxVal) * chartH;
    return { x, y, ...d };
  });

  const linePath = points.map((p, i) => (i === 0 ? `M ${p.x} ${p.y}` : `L ${p.x} ${p.y}`)).join(" ");
  const areaPath = `${linePath} L ${points[points.length - 1].x} ${padT + chartH} L ${points[0].x} ${padT + chartH} Z`;

  const yTicks = 5;
  let gridSvg = "";
  for (let i = 0; i <= yTicks; i++) {
    const val = Math.round((maxVal / yTicks) * i);
    const y = padT + chartH - (val / maxVal) * chartH;
    gridSvg += `<line x1="${padL}" y1="${y}" x2="${W - padR}" y2="${y}" stroke="#f1f5f9" stroke-width="1"/>`;
    gridSvg += `<text x="${padL - 8}" y="${y + 4}" font-size="10" fill="#94a3b8" text-anchor="end">${val >= 1000 ? (val/1000)+'k' : val}</text>`;
  }

  const labelsSvg = points.map(p => `<text x="${p.x}" y="${H - 8}" font-size="10" fill="#94a3b8" text-anchor="middle">${p.month}</text>`).join("");
  const guideSvg = `<line id="revenueGuideline" x1="0" y1="${padT}" x2="0" y2="${padT + chartH}" stroke="#94a3b8" stroke-width="1" stroke-dasharray="3,3" opacity="0"/>`;
  const dotsSvg = points.map((p, i) => `<circle class="revenue-dot" data-index="${i}" cx="${p.x}" cy="${p.y}" r="4" fill="#38bdf8" stroke="white" stroke-width="2"/>`).join("");
  const highlightDotSvg = `<circle id="revenueHighlightDot" cx="0" cy="0" r="6" fill="#0284c7" stroke="white" stroke-width="2.5" opacity="0" style="transition: opacity .1s ease;"/>`;

  let hoverZones = "";
  points.forEach((p, i) => {
    const prevX = i === 0 ? padL : (points[i - 1].x + p.x) / 2;
    const nextX = i === points.length - 1 ? (W - padR) : (points[i + 1].x + p.x) / 2;
    hoverZones += `<rect class="revenue-hover-zone" data-index="${i}" x="${prevX}" y="${padT}" width="${nextX - prevX}" height="${chartH}" fill="transparent" style="cursor: pointer;"/>`;
  });

  svg.innerHTML = `
    <defs>
      <linearGradient id="revGrad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.25"/>
        <stop offset="100%" stop-color="#38bdf8" stop-opacity="0"/>
      </linearGradient>
    </defs>
    ${gridSvg}
    <path d="${areaPath}" fill="url(#revGrad)"/>
    <path d="${linePath}" fill="none" stroke="#38bdf8" stroke-width="2.5"/>
    ${guideSvg}
    ${dotsSvg}
    ${highlightDotSvg}
    ${labelsSvg}
    ${hoverZones}
  `;

  const guideline = document.getElementById("revenueGuideline");
  const highlightDot = document.getElementById("revenueHighlightDot");

  function showPoint(i) {
    const p = points[i];
    guideline.setAttribute("x1", p.x);
    guideline.setAttribute("x2", p.x);
    guideline.setAttribute("opacity", "1");
    highlightDot.setAttribute("cx", p.x);
    highlightDot.setAttribute("cy", p.y);
    highlightDot.setAttribute("opacity", "1");
    document.querySelectorAll(".revenue-dot").forEach(dot => {
      dot.setAttribute("r", Number(dot.dataset.index) === i ? "5" : "4");
    });
    const svgRect = svg.getBoundingClientRect();
    const scaleX = svgRect.width / W;
    const scaleY = svgRect.height / H;
    const pxX = p.x * scaleX;
    const pxY = p.y * scaleY;
    tooltipMonth.textContent = p.month;
    tooltipValue.textContent = "₱" + p.value.toLocaleString();
    tooltip.classList.remove("hidden");
    const tooltipWidthEstimate = 90;
    let left = pxX - tooltipWidthEstimate / 2;
    left = Math.max(0, Math.min(left, svgRect.width - tooltipWidthEstimate));
    tooltip.style.left = left + "px";
    tooltip.style.top = Math.max(0, pxY - 52) + "px";
  }

  function hidePoint() {
    guideline.setAttribute("opacity", "0");
    highlightDot.setAttribute("opacity", "0");
    document.querySelectorAll(".revenue-dot").forEach(dot => dot.setAttribute("r", "4"));
    tooltip.classList.add("hidden");
  }

  document.querySelectorAll(".revenue-hover-zone").forEach(zone => {
    zone.addEventListener("mouseenter", () => showPoint(Number(zone.dataset.index)));
    zone.addEventListener("mousemove", () => showPoint(Number(zone.dataset.index)));
  });
  svg.addEventListener("mouseleave", hidePoint);
}

// ESTEBAN — revenue chart: line chart with gradient area fill, tooltip, hover zones (copied from original layout JS)
function renderCategoryChart() {
  const catData = window._categoryStockData || [];
  const svg = document.getElementById("categoryChart");
  const W = 320, H = 160, padL = 55, padR = 15, padT = 5, padB = 15;
  const chartW = W - padL - padR, chartH = H - padT - padB;
  const maxVal = Math.max(...catData.map(d => d.value), 1) * 1.2;
  const barH = (chartH / Math.max(catData.length, 1)) * 0.55;
  const gap = (chartH / Math.max(catData.length, 1));

  let bars = "";
  catData.forEach((d, i) => {
    const y = padT + i * gap + (gap - barH) / 2;
    const w = (d.value / maxVal) * chartW;
    bars += `
      <text x="${padL - 8}" y="${y + barH/2 + 4}" font-size="10" fill="#64748b" text-anchor="end">${d.label}</text>
      <rect x="${padL}" y="${y}" width="${chartW}" height="${barH}" rx="3" fill="#eef2ff"/>
      <rect x="${padL}" y="${y}" width="${w}" height="${barH}" rx="3" fill="#152a6e">
        <title>${d.label}: ${d.value}</title>
      </rect>
    `;
  });

  const tickCount = 5;
  let axisLabels = '';
  for (let i = 0; i <= tickCount; i++) {
    const v = Math.round((maxVal / tickCount) * i);
    const x = padL + (i / tickCount) * chartW;
    axisLabels += `<text x="${x}" y="${H - 4}" font-size="9" fill="#94a3b8" text-anchor="middle">${v}</text>`;
  }

  svg.innerHTML = bars + axisLabels;
}

// ESTEBAN — category chart: horizontal bar chart with inline labels, dynamic maxVal (copied from original layout JS)
async function renderWarehouseList() {
  const warehouses = await apiGet('/inventory/warehouses');
  const list = document.getElementById("warehouseList");
  list.innerHTML = warehouses.map(w => {
    const isSynced = w.status === "Synced";
    const badgeCls = isSynced ? "bg-emerald-100 text-emerald-600" : "bg-amber-100 text-amber-600";
    return `
      <div class="flex items-center justify-between py-3">
        <div>
          <div class="text-sm font-medium text-slate-800">${w.name}</div>
          <div class="text-xs text-slate-400">${w.detail} - Last sync ${w.lastSync}</div>
        </div>
        <span class="text-xs font-medium px-2.5 py-1 rounded-full ${badgeCls}">${w.status}</span>
      </div>
    `;
  }).join("");

  const activeCount = warehouses.filter(w => w.status === "Synced").length;
  document.getElementById("warehouseActiveCount").textContent = `${activeCount}/${warehouses.length} Active`;
}

// ESTEBAN — warehouse list: status badge, active count (copied from original layout JS)
function renderLowStockList() {
  const alerts = window._lowStockAlerts || [];
  const list = document.getElementById("lowStockList");
  list.innerHTML = alerts.map(item => {
    const pct = Math.round((item.left / item.max) * 100);
    return `
      <div>
        <div class="flex items-center justify-between text-sm">
          <div>
            <span class="font-medium text-slate-800">${item.name}</span>
            <span class="text-xs text-slate-400 ml-1">${item.sku}</span>
          </div>
          <span class="text-xs font-semibold text-red-500">${item.left} left</span>
        </div>
        <div class="w-full h-1.5 bg-gray-100 rounded-full mt-1.5 overflow-hidden">
          <div class="h-full bg-red-400 rounded-full" style="width:${pct}%"></div>
        </div>
        <div class="text-[10px] text-slate-400 mt-1">${pct}% of max stock (${item.max})</div>
      </div>
    `;
  }).join("");

  document.getElementById("lowStockCount").textContent = `${alerts.length} SKUs`;
}

// ESTEBAN — low stock: progress bar, percentage of max, left count (copied from original layout JS)
async function renderInventoryPage() {
  await renderInventoryStats();
  await renderRevenueChart();
  await renderWarehouseList();
}

// ESTEBAN — combined loader (matches goToPage('inventory') from original)

/* ---------------------------------------------------------
   EVENT BINDINGS
   ESTEBAN — forcedSync + exportReport copied from original layout JS
-------------------------------------------------------- */
document.getElementById("forcedSyncBtn").addEventListener("click", async function () {
  const btn = this;
  btn.disabled = true;
  const originalText = btn.textContent;
  btn.textContent = "Syncing...";
  btn.classList.add("opacity-70");

  await apiPost('/inventory/sync', new FormData());
  document.getElementById("lastSyncText").textContent = "Just now";
  await renderWarehouseList();
  btn.disabled = false;
  btn.textContent = originalText;
  btn.classList.remove("opacity-70");
  showToast("All warehouses forced-synced successfully");
});

document.getElementById("exportReportBtn").addEventListener("click", async () => {
  const stats = await apiGet('/inventory/stats');
  const warehouses = await apiGet('/inventory/warehouses');

  const rows = [["Metric", "Value"]];
  rows.push(["Total Product", stats.totalProduct]);
  rows.push(["Available Stock", stats.availableStock]);
  rows.push(["Low Stock Product", stats.lowStockProduct]);
  rows.push(["Out of Stock", stats.outOfStock]);
  rows.push([]);
  rows.push(["Warehouse", "Products", "Last Sync", "Status"]);
  warehouses.forEach(w => rows.push([w.name, w.detail, w.lastSync, w.status]));
  rows.push([]);
  rows.push(["Low Stock Item", "SKU", "Units Left", "Max Stock"]);
  (stats.lowStockAlerts || []).forEach(i => rows.push([i.name, i.sku, i.left, i.max]));

  const csv = rows.map(r => r.map(v => `"${v}"`).join(",")).join("\n");
  const blob = new Blob([csv], { type: "text/csv" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = "inventory_report.csv";
  a.click();
  URL.revokeObjectURL(url);
  showToast("Inventory report downloaded");
});

/* ---------------------------------------------------------
   INIT
-------------------------------------------------------- */
(async function init() {
  await renderInventoryPage();
})();
</script>

@endsection
