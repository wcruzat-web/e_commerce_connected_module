{{-- [ESTEBAN] SPA — fetches from /api/admin/products, CRUD modals via JS --}}
@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">
    @include('components.admin.sidebar')
    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')
        <div class="p-6" id="productApp">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                    <p class="text-sm text-gray-400 mt-1" id="productCount">Loading...</p>
                </div>
                <button onclick="openAddModal()" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Product
                </a>
            </div>

            <div id="alertBox" class="hidden mb-4"></div>

            <div class="bg-white rounded-2xl border border-gray-200/80 p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
                    <input type="text" id="searchInput" placeholder="Name or SKU..." onkeyup="if(event.key==='Enter') loadProducts()" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Brand</label>
                    <input type="text" id="brandInput" placeholder="Brand..." onkeyup="if(event.key==='Enter') loadProducts()" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Stock</label>
                    <select id="stockFilter" onchange="loadProducts()" class="rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">All</option>
                        <option value="In Stock">In Stock</option>
                        <option value="Low Stock">Low Stock</option>
                        <option value="Out of Stock">Out of Stock</option>
                    </select>
                </div>
                <button onclick="loadProducts()" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Filter</button>
                <button onclick="resetFilters()" class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-2">Reset</button>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-600">Product</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">SKU</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Category</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Price</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Stock</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Featured</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody" class="divide-y divide-gray-100">
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add/Edit Modal --}}
<div id="productModal" class="fixed inset-0 bg-black/30 z-50 hidden flex items-center justify-center p-4" onclick="if(event.target===this)closeModal()">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900" id="modalTitle">Add Product</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="productForm" class="space-y-4" onsubmit="return saveProduct(event)">
            <input type="hidden" id="editId">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name</label>
                <input type="text" id="fProductName" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
                    <input type="text" id="fSku" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Brand</label>
                    <input type="text" id="fBrand" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                    <input type="text" id="fCategory" required placeholder="e.g. GPU, CPU..." class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" id="fPrice" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                    <input type="number" id="fStock" min="0" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                    <input type="file" id="fImage" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold text-sm px-6 py-3 rounded-lg transition-colors" id="submitBtn">Create Product</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
const API = '/api/admin';
let products = [];

async function loadProducts() {
    const params = new URLSearchParams();
    const s = document.getElementById('searchInput').value; if(s) params.set('search', s);
    const b = document.getElementById('brandInput').value; if(b) params.set('brand', b);
    const st = document.getElementById('stockFilter').value; if(st) params.set('stock', st);
    try {
        const r = await fetch(API + '/products?' + params.toString(), { headers: { 'Accept': 'application/json' }});
        products = await r.json();
        renderTable();
    } catch(e) { console.error(e); }
}

function renderTable() {
    const tbody = document.getElementById('productTableBody');
    document.getElementById('productCount').textContent = products.length + ' products';
    if (!products.length) { tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No products found.</td></tr>'; return; }
    tbody.innerHTML = products.map(p => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    ${p.product_image ? `<img src="/storage/${p.product_image}" alt="${p.product_name}" class="w-10 h-10 rounded-lg object-cover bg-gray-100">` : '<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">N/A</div>'}
                    <div><p class="font-semibold text-gray-900">${esc(p.product_name)}</p><p class="text-xs text-gray-400">${esc(p.brand)}</p></div>
                </div>
            </td>
            <td class="px-4 py-3 text-gray-500 font-mono text-xs">${esc(p.sku)}</td>
            <td class="px-4 py-3">${esc(p.category)}</td>
            <td class="px-4 py-3 font-semibold">₱${Number(p.price).toFixed(2)}</td>
            <td class="px-4 py-3"><span class="${p.stock > 5 ? 'text-green-600' : p.stock > 0 ? 'text-amber-600' : 'text-red-600'} font-medium">${p.stock}</span></td>
            <td class="px-4 py-3">
                <button onclick="toggleFeatured(${p.product_id})" class="text-xl ${p.is_featured ? 'text-amber-400' : 'text-gray-300 hover:text-amber-300'}">★</button>
            </td>
            <td class="px-4 py-3">
                <div class="flex gap-2 items-center">
                    <button onclick="openEditModal(${p.product_id})" class="text-blue-600 hover:underline font-semibold text-xs">Edit</button>
                    <button onclick="deleteProduct(${p.product_id})" class="text-red-500 hover:text-red-700 hover:underline font-semibold text-xs">Remove</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function esc(s) { return s ? s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') : ''; }

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('brandInput').value = '';
    document.getElementById('stockFilter').value = '';
    loadProducts();
}

function showAlert(msg, type) {
    const box = document.getElementById('alertBox');
    box.className = 'mb-4 px-4 py-3 rounded-xl text-sm font-semibold ' + (type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200');
    box.textContent = msg; box.classList.remove('hidden');
    setTimeout(() => box.classList.add('hidden'), 4000);
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('submitBtn').textContent = 'Create Product';
    document.getElementById('editId').value = '';
    document.getElementById('productForm').reset();
    document.getElementById('productModal').classList.remove('hidden');
}

function openEditModal(id) {
    const p = products.find(x => x.product_id === id); if (!p) return;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('submitBtn').textContent = 'Update Product';
    document.getElementById('editId').value = p.product_id;
    document.getElementById('fProductName').value = p.product_name;
    document.getElementById('fSku').value = p.sku;
    document.getElementById('fBrand').value = p.brand;
    document.getElementById('fCategory').value = p.category || '';
    document.getElementById('fPrice').value = p.price;
    document.getElementById('fStock').value = p.stock;
    document.getElementById('productModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
}

async function saveProduct(e) {
    e.preventDefault();
    const formData = new FormData();
    const editId = document.getElementById('editId').value;
    formData.append('product_name', document.getElementById('fProductName').value);
    formData.append('sku', document.getElementById('fSku').value);
    formData.append('brand', document.getElementById('fBrand').value);
    formData.append('category', document.getElementById('fCategory').value);
    formData.append('price', document.getElementById('fPrice').value);
    formData.append('stock', document.getElementById('fStock').value || 0);
    const img = document.getElementById('fImage').files[0];
    if (img) formData.append('product_image', img);

    const url = editId ? API + '/products/' + editId : API + '/products';
    const method = editId ? 'PUT' : 'POST';
    if (editId) formData.append('_method', 'PUT');

    try {
        const r = await fetch(url, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
        const d = await r.json();
        if (d.success || d.product) {
            showAlert(d.message || (editId ? 'Product updated!' : 'Product created!'), 'success');
            closeModal(); loadProducts();
        } else showAlert('Error saving product', 'error');
    } catch(e) { showAlert('Error saving product', 'error'); }
}

async function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;
    try {
        const r = await fetch(API + '/products/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
        const d = await r.json();
        if (d.success) { showAlert(d.message, 'success'); loadProducts(); }
        else showAlert('Error deleting product', 'error');
    } catch(e) { showAlert('Error deleting product', 'error'); }
}

async function toggleFeatured(id) {
    try {
        const r = await fetch(API + '/products/' + id + '/featured', { method: 'PATCH', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
        const d = await r.json();
        if (d.success) loadProducts();
    } catch(e) {}
}

loadProducts();
</script>
@endpush
@endsection
