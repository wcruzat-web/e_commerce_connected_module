{{-- [ESTEBAN] SPA — fetches from /api/admin/inventory/*, stats/warehouses/revenue via JS --}}
@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">
    @include('components.admin.sidebar')
    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')
        <div class="p-6" id="inventoryApp">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Inventory Monitoring</h1>

            <div id="alertBox" class="hidden mb-4"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="statCards">
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm animate-pulse"><div class="h-4 bg-gray-200 rounded w-24 mb-2"></div><div class="h-8 bg-gray-200 rounded w-16"></div></div>
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm animate-pulse"><div class="h-4 bg-gray-200 rounded w-24 mb-2"></div><div class="h-8 bg-gray-200 rounded w-16"></div></div>
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm animate-pulse"><div class="h-4 bg-gray-200 rounded w-24 mb-2"></div><div class="h-8 bg-gray-200 rounded w-16"></div></div>
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm animate-pulse"><div class="h-4 bg-gray-200 rounded w-24 mb-2"></div><div class="h-8 bg-gray-200 rounded w-16"></div></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-800 mb-4">Stock by Category</h2>
                    <div id="categoryStock"><p class="text-sm text-gray-400">Loading...</p></div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-800 mb-4">Low Stock Alerts</h2>
                    <div id="lowStockAlerts"><p class="text-sm text-gray-400">Loading...</p></div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-800">Product Stock</h2>
                    <div class="flex gap-2">
                        <select id="stockFilter" onchange="filterStock()" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">All</option>
                            <option value="available">Available (&gt;5)</option>
                            <option value="low">Low Stock (1–5)</option>
                            <option value="out">Out of Stock (0)</option>
                        </select>
                        <input id="stockSearch" onkeyup="filterStock()" placeholder="Search product..." class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 w-40">
                    </div>
                </div>
                <div id="productStockTable" class="overflow-x-auto"><p class="text-sm text-gray-400">Loading...</p></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-800">Warehouses</h2>
                        <button onclick="syncWarehouses()" class="text-xs font-semibold bg-blue-900 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg transition-colors">Sync All</button>
                    </div>
                    <div id="warehouseList"><p class="text-sm text-gray-400">Loading...</p></div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-800 mb-4">Revenue Overview</h2>
                    <div id="revenueTable"><p class="text-sm text-gray-400">Loading...</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const API = '/api/admin';

async function loadStats() {
    try {
        const r = await fetch(API + '/inventory/stats', { headers: { 'Accept': 'application/json' }});
        const d = await r.json();
        document.getElementById('statCards').innerHTML = `
            <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm"><p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Products</p><p class="text-3xl font-black text-gray-900 mt-1">${d.totalProduct}</p></div>
            <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm"><p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Available Stock</p><p class="text-3xl font-black text-green-600 mt-1">${d.availableStock}</p></div>
            <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm"><p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Low Stock</p><p class="text-3xl font-black text-amber-500 mt-1">${d.lowStockProduct}</p></div>
            <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-sm"><p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Out of Stock</p><p class="text-3xl font-black text-red-500 mt-1">${d.outOfStock}</p></div>
        `;
        document.getElementById('categoryStock').innerHTML = (d.categoryStock || []).length
            ? d.categoryStock.map(c => `<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0"><span class="text-sm font-medium text-gray-600">${esc(c.label)}</span><span class="text-sm font-bold text-gray-900">${c.value}</span></div>`).join('')
            : '<p class="text-sm text-gray-400">No data</p>';
        document.getElementById('lowStockAlerts').innerHTML = (d.lowStockAlerts || []).length
            ? d.lowStockAlerts.map(a => `<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0"><div><p class="text-sm font-semibold text-gray-900" title="${esc(a.name)}">${esc(a.name)}</p><p class="text-xs text-gray-400">${esc(a.sku)}</p></div><div class="text-right"><span class="text-sm font-bold ${a.left > 0 ? 'text-amber-500' : 'text-red-500'}">${a.left}</span><span class="text-xs text-gray-400"> / ${a.max}</span></div></div>`).join('')
            : '<p class="text-sm text-gray-400">No low stock alerts</p>';
    } catch(e) { console.error(e); }
}

async function loadWarehouses() {
    try {
        const r = await fetch(API + '/inventory/warehouses', { headers: { 'Accept': 'application/json' }});
        const data = await r.json();
        document.getElementById('warehouseList').innerHTML = data.length
            ? data.map(w => `<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0"><div><p class="text-sm font-semibold text-gray-900">${esc(w.name)}</p><p class="text-xs text-gray-400">${esc(w.detail)} · ${esc(w.lastSync)}</p></div><span class="text-xs font-semibold px-2 py-1 rounded ${w.status === 'Synced' ? 'bg-green-100 text-green-700' : w.status === 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'}">${esc(w.status)}</span></div>`).join('')
            : '<p class="text-sm text-gray-400">No warehouses configured</p>';
    } catch(e) { console.error(e); }
}

async function loadRevenue() {
    try {
        const r = await fetch(API + '/revenue', { headers: { 'Accept': 'application/json' }});
        const data = await r.json();
        document.getElementById('revenueTable').innerHTML = data.length
            ? data.map(rv => `<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0"><span class="text-sm font-medium text-gray-600">${esc(rv.month)}</span><span class="text-sm font-bold text-gray-900">₱${Number(rv.value).toFixed(2)}</span></div>`).join('')
            : '<p class="text-sm text-gray-400">No revenue data</p>';
    } catch(e) { console.error(e); }
}

let allProducts = [];

async function loadProducts() {
    try {
        const r = await fetch(API + '/inventory/products', { headers: { 'Accept': 'application/json' }});
        allProducts = await r.json();
        renderProducts(allProducts);
    } catch(e) { console.error(e); }
}

function renderProducts(products) {
    const table = document.getElementById('productStockTable');
    if (!products.length) {
        table.innerHTML = '<p class="text-sm text-gray-400">No products found</p>';
        return;
    }
    table.innerHTML = `<table class="w-full text-sm">
        <thead><tr class="border-b border-gray-200 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
            <th class="pb-2 pr-2">Product</th><th class="pb-2 pr-2">Category</th><th class="pb-2 pr-2">Stock</th><th class="pb-2">Status</th>
        </tr></thead><tbody>${products.map(p => {
            const badge = p.status === 'available' ? 'bg-green-100 text-green-700'
                        : p.status === 'low' ? 'bg-amber-100 text-amber-700'
                        : 'bg-red-100 text-red-700';
            const label = p.status === 'available' ? 'Available'
                        : p.status === 'low' ? 'Low Stock'
                        : 'Out of Stock';
            return `<tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-2 pr-2 font-semibold text-gray-900">${esc(p.product_name)}</td>
                <td class="py-2 pr-2 text-gray-500">${esc(p.category)}</td>
                <td class="py-2 pr-2 font-bold ${p.stock > 0 ? 'text-gray-900' : 'text-red-500'}">${p.stock}</td>
                <td class="py-2"><span class="text-xs font-semibold px-2 py-1 rounded ${badge}">${label}</span></td>
            </tr>`;
        }).join('')}</tbody></table>`;
}

function filterStock() {
    const filter = document.getElementById('stockFilter').value;
    const query = document.getElementById('stockSearch').value.toLowerCase();
    let filtered = allProducts;
    if (filter !== 'all') filtered = filtered.filter(p => p.status === filter);
    if (query) filtered = filtered.filter(p => p.product_name.toLowerCase().includes(query));
    renderProducts(filtered);
}

async function syncWarehouses() {
    try {
        const r = await fetch(API + '/inventory/sync', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
        const d = await r.json();
        if (d.success) {
            showAlert('All warehouses synced successfully', 'success');
            loadWarehouses();
        }
    } catch(e) {}
}

function showAlert(msg, type) {
    const box = document.getElementById('alertBox');
    box.className = 'mb-4 px-4 py-3 rounded-xl text-sm font-semibold ' + (type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200');
    box.textContent = msg; box.classList.remove('hidden');
    setTimeout(() => box.classList.add('hidden'), 4000);
}

function esc(s) { return s ? s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') : ''; }

loadStats();
loadProducts();
loadWarehouses();
loadRevenue();
</script>
@endpush
@endsection
