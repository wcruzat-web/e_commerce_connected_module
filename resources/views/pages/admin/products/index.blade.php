@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">

    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">

        @include('pages.admin.dashboard.components.topbar')

        <div class="p-4 lg:p-6">
            <x-admin.product />
        </div>

    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 z-50"></div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-40 p-4">
  <div class="modal-in bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h2 id="modalTitle" class="text-lg font-bold text-slate-900">Add Product</h2>
      <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="productForm" class="flex flex-col gap-3" enctype="multipart/form-data">
      <input type="hidden" id="productId">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-slate-500">Product Name</label>
          <input required id="pName" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="e.g. NVIDIA RTX 4090 Founder Edition">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Brand</label>
          <input required id="pBrand" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="e.g. NVIDIA">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">SKU</label>
          <input required id="pSku" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="NV-4090-FE">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Category</label>
          <input id="pCategory" list="categoryList" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="e.g. GPU">
          <datalist id="categoryList"></datalist>
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Price ($)</label>
          <input required id="pPrice" type="number" step="0.01" min="0" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="1599">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Stock Qty</label>
          <input required id="pStock" type="number" min="0" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="2">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-slate-500">Badge</label>
          <div class="flex items-center gap-4 mt-1">
            <label class="flex items-center gap-1.5 text-sm"><input type="radio" name="pBadge" value="" checked> None</label>
            <label class="flex items-center gap-1.5 text-sm"><input type="radio" name="pBadge" value="New"> New</label>
            <label class="flex items-center gap-1.5 text-sm"><input type="radio" name="pBadge" value="Sale"> Sale</label>
          </div>
        </div>
        <div id="originalPriceWrapper" class="hidden">
          <label class="text-xs font-medium text-slate-500">Original Price ($)</label>
          <input id="pOriginalPrice" type="number" step="0.01" min="0" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="1999">
        </div>
      </div>

      <div>
        <label class="text-xs font-medium text-slate-500">Product Image</label>
        <input id="pImage" type="file" accept="image/*" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
        <div id="imagePreview" class="mt-2 hidden">
          <img id="imagePreviewImg" src="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
        </div>
      </div>

      <div class="border-t border-gray-200 pt-3 mt-1">
        <div class="flex items-center justify-between mb-2">
          <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Specifications</label>
          <button type="button" id="addSpecBtn" class="text-xs text-sky-500 hover:text-sky-600 font-medium">+ Add Row</button>
        </div>
        <div id="specsContainer" class="flex flex-col gap-2"></div>
      </div>

      <div class="border-t border-gray-200 pt-3">
        <div class="flex items-center justify-between mb-2">
          <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Compatibility</label>
          <button type="button" id="addCompatBtn" class="text-xs text-sky-500 hover:text-sky-600 font-medium">+ Add Row</button>
        </div>
        <div id="compatContainer" class="flex flex-col gap-2"></div>
      </div>

      <div class="flex justify-end gap-2 mt-2">
        <button type="button" id="cancelModalBtn" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition">Cancel</button>
        <button type="submit" id="saveProductBtn" class="px-4 py-2 text-sm font-medium bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">Save Product</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-40 p-4">
  <div class="modal-in bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
    </div>
    <h3 class="font-semibold text-slate-900 mb-1">Delete Product</h3>
    <p class="text-sm text-slate-500 mb-5">Are you sure you want to delete <span id="deleteProductName" class="font-medium text-slate-700"></span>? This can't be undone.</p>
    <div class="flex gap-2">
      <button id="cancelDeleteBtn" class="flex-1 px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition">Cancel</button>
      <button id="confirmDeleteBtn" class="flex-1 px-4 py-2 text-sm font-medium bg-red-500 hover:bg-red-600 text-white rounded-lg transition">Delete</button>
    </div>
  </div>
</div>

<style>
  .fade-in { animation: fadeIn .15s ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px);} to { opacity: 1; transform: translateY(0);} }
  .modal-in { animation: modalIn .18s ease-out; }
  @keyframes modalIn { from { opacity: 0; transform: scale(.96);} to { opacity: 1; transform: scale(1);} }
  @media (min-width: 1280px) {
    #page-product { display: grid !important; grid-template-columns: 1fr 300px !important; }
  }
</style>

<script>
/* ---------------------------------------------------------
   DATA - loaded from database via AJAX
--------------------------------------------------------- */
let products = [];
let promoBanners = [];
let allBrands = [];

// ESTEBAN — adapted: category colors for badge rendering in table
const categoryColors = {
  GPU: "bg-sky-100 text-sky-600",
  CPU: "bg-blue-100 text-blue-600",
  Motherboard: "bg-indigo-100 text-indigo-600",
  Memory: "bg-emerald-100 text-emerald-600",
  Cooling: "bg-cyan-100 text-cyan-600",
};

// ESTEBAN — adapted: short labels for featured preview chart
const categoryToChartLabel = {
  GPU: "GPU", CPU: "CPU", Motherboard: "MB", Memory: "RAM", Cooling: "Cooling",
};

// ESTEBAN — adapted: max stock thresholds for low-stock progress bars
const categoryMaxStock = {
  GPU: 50, CPU: 100, Motherboard: 30, Memory: 80, Cooling: 40,
};

let formSpecs = [];
let formCompat = [];

/* ---------------------------------------------------------
   API HELPERS
--------------------------------------------------------- */
// ESTEBAN — changed: API_BASE='/api/admin' (was '')
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

async function apiPut(url, formData) {
  const res = await fetch(API_BASE + url, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    body: formData,
  });
  return res.json();
}

async function apiDelete(url) {
  const res = await fetch(API_BASE + url, {
    method: 'DELETE',
    headers: apiHeaders(),
  });
  return res.json();
}

async function apiPatch(url) {
  const res = await fetch(API_BASE + url, {
    method: 'PATCH',
    headers: apiHeaders(),
  });
  return res.json();
}

/* ---------------------------------------------------------
   DATA LOADING
--------------------------------------------------------- */
// ESTEBAN — adapted: column mapping uses 'id','name','featured_image' (was 'product_id','product_name','product_image'); added URLSearchParams for filters
async function loadProducts() {
  const params = new URLSearchParams();
  if (filters.search) params.set('search', filters.search);
  if (filters.category !== 'All') params.set('category', filters.category);
  if (filters.brand !== 'All Brands') params.set('brand', filters.brand);
  if (filters.stock !== 'All') params.set('stock', filters.stock);

  const data = await apiGet('/products?' + params.toString());
  products = data.map(p => ({
    id: p.id,
    name: p.name,
    brand: p.brand,
    sku: p.sku,
    category: p.category,
    price: parseFloat(p.price),
    stock: p.stock,
    badge: p.badge || '',
    sale_price: p.sale_price || null,
    featured: p.is_featured,
    image: p.featured_image,
    image_url: p.image_url,
    specifications: p.specifications || [],
    compatibilities: p.compatibilities || [],
  }));
  renderTable();
  renderFeatured();
  if (allBrands.length === 0) {
    allBrands = [...new Set(data.map(p => p.brand))].sort();
    populateBrandFilter();
  }
}

async function loadPromos() {
  const data = await apiGet('/promos');
  promoBanners = data.map(b => ({
    id: b.banner_id,
    title: b.title,
    subtitle: b.subtitle,
  }));
  renderPromos();
}

/* ---------------------------------------------------------
   CORE FUNCTIONS
--------------------------------------------------------- */
const PAGE_SIZE = 5;
let currentPage = 1;
let filters = { search: "", category: "All", brand: "All Brands", stock: "All" };
let editingId = null;
let deletingId = null;
let selectedPreviewId = null;

function getStockStatus(stock) {
  if (stock === 0) return { label: "Out of Stock", cls: "bg-red-100 text-red-500" };
  if (stock <= 5) return { label: "Low Stock", cls: "bg-amber-100 text-amber-600" };
  return { label: "In Stock", cls: "bg-emerald-100 text-emerald-600" };
}

function showToast(message) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.classList.remove("opacity-0", "pointer-events-none");
  clearTimeout(window._toastTimer);
  window._toastTimer = setTimeout(() => {
    toast.classList.add("opacity-0", "pointer-events-none");
  }, 2200);
}

function getFilteredProducts() {
  return products.filter(p => {
    const matchesSearch =
      filters.search === "" ||
      p.name.toLowerCase().includes(filters.search.toLowerCase()) ||
      p.sku.toLowerCase().includes(filters.search.toLowerCase());
    const matchesCategory = filters.category === "All" || p.category === filters.category;
    const matchesBrand = filters.brand === "All Brands" || p.brand === filters.brand;
    const status = getStockStatus(p.stock).label;
    const matchesStock = filters.stock === "All" || status === filters.stock;
    return matchesSearch && matchesCategory && matchesBrand && matchesStock;
  });
}

function renderTable() {
  const filtered = getFilteredProducts();
  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  if (currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * PAGE_SIZE;
  const pageItems = filtered.slice(start, start + PAGE_SIZE);

  const tbody = document.getElementById("productTableBody");
  tbody.innerHTML = "";

  if (pageItems.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" class="py-10 text-center text-slate-400 text-sm">No products match your filters.</td></tr>`;
  }

  pageItems.forEach(p => {
    const status = getStockStatus(p.stock);
    const catCls = categoryColors[p.category] || "bg-gray-100 text-gray-600";
    const imgHtml = p.image_url
      ? `<img src="${p.image_url}" class="w-10 h-10 rounded-lg object-cover border border-gray-200" alt="${p.name}">`
      : `<div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        </div>`;
    const tr = document.createElement("tr");
    tr.dataset.rowId = p.id;
    tr.className = "hover:bg-slate-50 fade-in" + (p.id === selectedPreviewId ? " bg-sky-50" : "");
    tr.innerHTML = `
      <td class="py-3 pr-2"><input type="checkbox" class="row-checkbox rounded border-gray-300" data-id="${p.id}"></td>
      <td class="py-3 pr-4">
        <div class="flex items-center gap-3">
          ${imgHtml}
          <div>
            <div class="font-medium text-slate-800">${p.name.length > 20 ? p.name.slice(0,20) + "..." : p.name}</div>
            <div class="text-xs text-slate-400">${p.brand}</div>
          </div>
        </div>
      </td>
      <td class="py-3 pr-4 text-slate-500">${p.sku}</td>
      <td class="py-3 pr-4"><span class="text-xs font-medium px-2 py-1 rounded-full ${catCls}">${p.category}</span></td>
      <td class="py-3 pr-4 font-medium text-slate-700">$${p.price.toLocaleString()}</td>
      <td class="py-3 pr-4 text-slate-600">${p.stock}</td>
      <td class="py-3 pr-4"><span class="text-xs font-medium px-2 py-1 rounded-full ${status.cls}">${status.label}</span></td>
      <td class="py-3 pr-2">
        <div class="flex items-center gap-2 text-slate-400">
          <button class="featured-btn" data-id="${p.id}" title="${p.featured ? 'Unfeature' : 'Feature'}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ${p.featured ? 'text-amber-400' : 'hover:text-amber-400'}" viewBox="0 0 24 24" fill="${p.featured ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
          </button>
          <button class="preview-btn hover:text-sky-500" data-id="${p.id}" title="Preview">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
          <button class="edit-btn hover:text-emerald-500" data-id="${p.id}" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="delete-btn hover:text-red-500" data-id="${p.id}" title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
          </button>
        </div>
      </td>
    `;
    tbody.appendChild(tr);
  });

  document.getElementById("showingText").textContent =
    filtered.length === 0
      ? "Showing 0 of 0 product."
      : `Showing ${start + 1}-${Math.min(start + PAGE_SIZE, filtered.length)} of ${filtered.length} product.`;

  renderPagination(totalPages);
  attachRowListeners();
}

function renderPagination(totalPages) {
  const container = document.getElementById("paginationControls");
  container.innerHTML = "";

  const prevBtn = document.createElement("button");
  prevBtn.innerHTML = "&lt;";
  prevBtn.className = "w-7 h-7 flex items-center justify-center rounded-full text-slate-400 hover:bg-gray-100 disabled:opacity-30";
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = () => { currentPage--; renderTable(); };
  container.appendChild(prevBtn);

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.className = i === currentPage
      ? "w-7 h-7 flex items-center justify-center rounded-full bg-sky-500 text-white font-medium"
      : "w-7 h-7 flex items-center justify-center rounded-full text-slate-500 hover:bg-gray-100";
    btn.onclick = () => { currentPage = i; renderTable(); };
    container.appendChild(btn);
  }

  const nextBtn = document.createElement("button");
  nextBtn.innerHTML = "&gt;";
  nextBtn.className = "w-7 h-7 flex items-center justify-center rounded-full text-slate-400 hover:bg-gray-100 disabled:opacity-30";
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = () => { currentPage++; renderTable(); };
  container.appendChild(nextBtn);
}

// ESTEBAN — added: featured-toggle star button with single-featured enforcement
function attachRowListeners() {
  document.querySelectorAll(".featured-btn").forEach(btn => {
    btn.onclick = async () => {
      const id = Number(btn.dataset.id);
      const res = await apiPatch('/products/' + id + '/featured');
      if (res.success) {
        const p = products.find(x => x.id === id);
        if (p) {
          products.forEach(x => x.featured = false);
          p.featured = !p.featured;
        }
        renderTable();
        renderFeatured();
        showToast(p.featured ? 'Product featured' : 'Product unfeatured');
      }
    };
  });
  document.querySelectorAll(".preview-btn").forEach(btn => {
    btn.onclick = () => previewProduct(Number(btn.dataset.id));
  });
  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.onclick = () => openEditModal(Number(btn.dataset.id));
  });
  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.onclick = () => openDeleteModal(Number(btn.dataset.id));
  });
}

function previewProduct(id) {
  const p = products.find(x => x.id === id);
  if (!p) return;

  selectedPreviewId = id;

  document.querySelectorAll("#productTableBody tr[data-row-id]").forEach(row => {
    row.classList.toggle("bg-sky-50", Number(row.dataset.rowId) === id);
  });

  const status = getStockStatus(p.stock);
  const catCls = categoryColors[p.category] || "bg-gray-100 text-gray-600";
  const pane = document.getElementById("previewPane");
  pane.className = "border border-gray-200 rounded-lg p-4 fade-in";

  const imgHtml = p.image_url
    ? `<img src="${p.image_url}" class="w-16 h-16 rounded-lg object-cover border border-gray-200" alt="${p.name}">`
    : `<div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
      </div>`;

  pane.innerHTML = `
    <div class="flex flex-col gap-2">
      <div class="flex items-start justify-between">
        ${imgHtml}
        <button id="clearPreviewBtn" class="text-slate-300 hover:text-slate-500" title="Clear preview">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="font-semibold text-sm text-slate-800">${p.name}</div>
      <div class="text-xs text-slate-400">${p.brand} &middot; SKU: ${p.sku}</div>
      <div class="flex items-center gap-2">
        <span class="text-xs font-medium px-2 py-1 rounded-full ${catCls}">${p.category}</span>
        <span class="text-xs font-medium px-2 py-1 rounded-full ${status.cls}">${status.label}</span>
      </div>
      <div class="flex items-center justify-between text-sm pt-1 border-t border-gray-100 mt-1">
        <span class="text-slate-400">Price</span>
        <span class="font-semibold text-slate-800">$${p.price.toLocaleString()}</span>
      </div>
      <div class="flex items-center justify-between text-sm">
        <span class="text-slate-400">Stock on hand</span>
        <span class="font-medium text-slate-700">${p.stock} units</span>
      </div>
    </div>
  `;

  document.getElementById("clearPreviewBtn").onclick = clearPreview;
}

function clearPreview() {
  selectedPreviewId = null;
  document.querySelectorAll("#productTableBody tr[data-row-id]").forEach(row => {
    row.classList.remove("bg-sky-50");
  });
  const pane = document.getElementById("previewPane");
  pane.className = "border border-dashed border-gray-200 rounded-lg h-40 flex flex-col items-center justify-center text-center px-4";
  pane.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
    <p class="text-xs text-slate-400">click the eye icon on a product row to<br>preview it here</p>
  `;
}

function renderFeatured() {
  const list = document.getElementById("featuredList");
  const featured = products.filter(p => p.featured);
  list.innerHTML = featured.length === 0
    ? `<div class="text-xs text-slate-400">No featured products yet.</div>`
    : featured.map(p => `
      <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2 text-xs font-medium text-amber-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-400 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        <span class="truncate">${p.name}</span>
      </div>
    `).join("");
}

function renderPromos() {
  const list = document.getElementById("promoList");
  list.innerHTML = promoBanners.map(b => `
    <div class="flex items-center justify-between bg-sky-50 border border-sky-100 rounded-lg px-3 py-2">
      <div>
        <div class="text-xs font-semibold text-slate-700">${b.title}</div>
        <div class="text-xs text-slate-400">${b.subtitle}</div>
      </div>
      <button class="promo-delete-btn text-slate-300 hover:text-red-500" data-id="${b.id}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
      </button>
    </div>
  `).join("");

  document.querySelectorAll(".promo-delete-btn").forEach(btn => {
    btn.onclick = async () => {
      await apiDelete('/promos/' + btn.dataset.id);
      promoBanners = promoBanners.filter(b => b.id !== Number(btn.dataset.id));
      renderPromos();
      showToast("Promo banner removed");
    };
  });
}

function makeCategoryOptions(cats) {
  return cats.map(c => `<option value="${c}">${c}</option>`).join("");
}

// ESTEBAN — added: Specifications dynamic rows (Section/Label/Value inputs)
function renderSpecRows() {
  const container = document.getElementById("specsContainer");
  container.innerHTML = formSpecs.map((s, i) => `
    <div class="flex gap-2 items-start spec-row">
      <input type="text" class="spec-cat text-xs border border-gray-300 rounded-lg px-2 py-1.5 w-40 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Section (e.g. Memory)" value="${s.category_name}">
      <input type="text" class="spec-label text-xs border border-gray-300 rounded-lg px-2 py-1.5 flex-1 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Label (e.g. GPU Architecture)" value="${s.label}">
      <input type="text" class="spec-value text-xs border border-gray-300 rounded-lg px-2 py-1.5 flex-1 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Value (e.g. 24GB)" value="${s.value}">
      <button type="button" class="remove-spec-btn text-red-400 hover:text-red-600 shrink-0 px-1" data-index="${i}">&times;</button>
    </div>
  `).join("");
  container.querySelectorAll(".remove-spec-btn").forEach(btn => {
    btn.onclick = () => { formSpecs.splice(Number(btn.dataset.index), 1); renderSpecRows(); };
  });
}

function renderCompatRows() {
  const container = document.getElementById("compatContainer");
  container.innerHTML = formCompat.map((c, i) => `
    <div class="flex gap-2 items-start compat-row">
      <input type="text" class="compat-cat text-xs border border-gray-300 rounded-lg px-2 py-1.5 w-40 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Type (e.g. Recommended PSU)" value="${c.category_name}">
      <input type="text" class="compat-item text-xs border border-gray-300 rounded-lg px-2 py-1.5 flex-1 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Item name (e.g. Corsair HX1000i)" value="${c.item_name}">
      <button type="button" class="remove-compat-btn text-red-400 hover:text-red-600 shrink-0 px-1" data-index="${i}">&times;</button>
    </div>
  `).join("");
  container.querySelectorAll(".remove-compat-btn").forEach(btn => {
    btn.onclick = () => { formCompat.splice(Number(btn.dataset.index), 1); renderCompatRows(); };
  });
}

document.getElementById("addSpecBtn").onclick = () => {
  formSpecs.push({ category_name: '', label: '', value: '' });
  renderSpecRows();
};

// ESTEBAN — added: Compatibility dynamic rows (Type/Item Name inputs)
document.getElementById("addCompatBtn").onclick = () => {
  formCompat.push({ category_name: '', item_name: '' });
  renderCompatRows();
};

function collectSpecData() {
  const rows = document.querySelectorAll(".spec-row");
  return Array.from(rows).map(row => ({
    category_name: row.querySelector(".spec-cat").value,
    label: row.querySelector(".spec-label").value,
    value: row.querySelector(".spec-value").value,
  })).filter(s => s.category_name && s.label);
}

function collectCompatData() {
  const rows = document.querySelectorAll(".compat-row");
  return Array.from(rows).map(row => ({
    category_name: row.querySelector(".compat-cat").value,
    item_name: row.querySelector(".compat-item").value,
  })).filter(c => c.category_name && c.item_name);
}

/* ---------------------------------------------------------
   PRODUCT MODAL
--------------------------------------------------------- */
function openAddModal() {
  editingId = null;
  document.getElementById("modalTitle").textContent = "Add Product";
  document.getElementById("productForm").reset();
  document.getElementById("imagePreview").classList.add("hidden");
  formSpecs = [];
  formCompat = [];
  renderSpecRows();
  renderCompatRows();
  document.getElementById("productModal").classList.remove("hidden");
  document.getElementById("productModal").classList.add("flex");
}

function openEditModal(id) {
  const p = products.find(x => x.id === id);
  if (!p) return;
  editingId = id;
  document.getElementById("modalTitle").textContent = "Edit Product";
  document.getElementById("pName").value = p.name;
  document.getElementById("pBrand").value = p.brand;
  document.getElementById("pSku").value = p.sku;
  document.getElementById("pCategory").value = p.category;
  document.getElementById("pPrice").value = p.price;
  document.getElementById("pStock").value = p.stock;
  document.getElementById("pImage").value = "";

  const badgeVal = p.badge || '';
  document.querySelectorAll('input[name="pBadge"]').forEach(r => r.checked = r.value === badgeVal);
  document.getElementById("originalPriceWrapper").classList.toggle("hidden", badgeVal !== "Sale");
  if (badgeVal === 'Sale' && p.sale_price) {
    document.getElementById("pOriginalPrice").value = p.sale_price;
  } else {
    document.getElementById("pOriginalPrice").value = '';
  }

  if (p.image_url) {
    document.getElementById("imagePreview").classList.remove("hidden");
    document.getElementById("imagePreviewImg").src = p.image_url;
  } else {
    document.getElementById("imagePreview").classList.add("hidden");
  }

  formSpecs = (p.specifications || []).map(s => ({
    category_name: s.category_name || '',
    label: s.label || '',
    value: s.value || '',
  }));
  formCompat = (p.compatibilities || []).map(c => ({
    category_name: c.category_name || '',
    item_name: c.item_name || '',
  }));
  renderSpecRows();
  renderCompatRows();

  document.getElementById("productModal").classList.remove("hidden");
  document.getElementById("productModal").classList.add("flex");
}

function closeProductModal() {
  document.getElementById("productModal").classList.add("hidden");
  document.getElementById("productModal").classList.remove("flex");
}

document.getElementById("pImage").addEventListener("change", function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById("imagePreviewImg").src = e.target.result;
      document.getElementById("imagePreview").classList.remove("hidden");
    };
    reader.readAsDataURL(file);
  }
});

document.querySelectorAll('input[name="pBadge"]').forEach(radio => {
  radio.addEventListener("change", () => {
    document.getElementById("originalPriceWrapper").classList.toggle("hidden", radio.value !== "Sale");
  });
});

// ESTEBAN — added: Badge (None/New/Sale) radio buttons + original price input; specs/compat JSON in FormData
document.getElementById("productForm").addEventListener("submit", async e => {
  e.preventDefault();
  const saveBtn = document.getElementById("saveProductBtn");
  saveBtn.disabled = true;
  saveBtn.textContent = "Saving...";

  const fd = new FormData();
  fd.append('name', document.getElementById("pName").value.trim());
  fd.append('brand', document.getElementById("pBrand").value.trim());
  fd.append('sku', document.getElementById("pSku").value.trim());
  fd.append('category', document.getElementById("pCategory").value);
  fd.append('price', parseFloat(document.getElementById("pPrice").value));
  fd.append('stock', parseInt(document.getElementById("pStock").value, 10));

  const badgeEl = document.querySelector('input[name="pBadge"]:checked');
  const badge = badgeEl ? badgeEl.value : '';
  fd.append('badge', badge);
  if (badge === 'Sale') {
    fd.append('sale_price', parseFloat(document.getElementById("pOriginalPrice").value) || 0);
  }

  const imageInput = document.getElementById("pImage");
  if (imageInput.files.length > 0) {
    fd.append('featured_image', imageInput.files[0]);
  }

  const specsData = collectSpecData();
  const compatData = collectCompatData();
  fd.append('specs', JSON.stringify(specsData));
  fd.append('compat', JSON.stringify(compatData));

  try {
    if (editingId) {
      fd.append('_method', 'PUT');
      await apiPut('/products/' + editingId, fd);
      showToast("Product updated successfully");
    } else {
      await apiPost('/products', fd);
      showToast("Product added — Inventory updated");
    }
    closeProductModal();
    await loadProducts();
    if (selectedPreviewId !== null && products.some(p => p.id === selectedPreviewId)) {
      previewProduct(selectedPreviewId);
    }
  } catch (err) {
    showToast("Error saving product");
  }

  saveBtn.disabled = false;
  saveBtn.textContent = "Save Product";
});

document.getElementById("addProductBtn").onclick = openAddModal;
document.getElementById("closeModalBtn").onclick = closeProductModal;
document.getElementById("cancelModalBtn").onclick = closeProductModal;

/* ---------------------------------------------------------
   DELETE
--------------------------------------------------------- */
function openDeleteModal(id) {
  const p = products.find(x => x.id === id);
  if (!p) return;
  deletingId = id;
  document.getElementById("deleteProductName").textContent = p.name;
  document.getElementById("deleteModal").classList.remove("hidden");
  document.getElementById("deleteModal").classList.add("flex");
}

function closeDeleteModal() {
  document.getElementById("deleteModal").classList.add("hidden");
  document.getElementById("deleteModal").classList.remove("flex");
  deletingId = null;
}

document.getElementById("cancelDeleteBtn").onclick = closeDeleteModal;
document.getElementById("confirmDeleteBtn").onclick = async () => {
  await apiDelete('/products/' + deletingId);
  if (selectedPreviewId === deletingId) clearPreview();
  showToast("Product deleted");
  closeDeleteModal();
  await loadProducts();
};

/* ---------------------------------------------------------
   FILTERS
--------------------------------------------------------- */
document.getElementById("searchInput").addEventListener("input", e => {
  filters.search = e.target.value;
  currentPage = 1;
  loadProducts();
});
document.getElementById("categoryFilter").addEventListener("change", e => {
  filters.category = e.target.value;
  currentPage = 1;
  loadProducts();
});
document.getElementById("brandFilter").addEventListener("change", e => {
  filters.brand = e.target.value;
  currentPage = 1;
  loadProducts();
});
document.getElementById("stockFilter").addEventListener("change", e => {
  filters.stock = e.target.value;
  currentPage = 1;
  loadProducts();
});

document.getElementById("selectAll").addEventListener("change", e => {
  document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = e.target.checked);
});

/* ---------------------------------------------------------
   EXPORT
--------------------------------------------------------- */
document.getElementById("exportBtn").addEventListener("click", () => {
  const rows = [["Product", "Brand", "SKU", "Category", "Price", "Stock", "Status"]];
  getFilteredProducts().forEach(p => {
    rows.push([p.name, p.brand, p.sku, p.category, p.price, p.stock, getStockStatus(p.stock).label]);
  });
  const csv = rows.map(r => r.map(v => `"${v}"`).join(",")).join("\n");
  const blob = new Blob([csv], { type: "text/csv" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = "products_export.csv";
  a.click();
  URL.revokeObjectURL(url);
  showToast("Export downloaded");
});

/* ---------------------------------------------------------
   MISC UI
--------------------------------------------------------- */
document.getElementById("addPromoBtn").onclick = async () => {
  const title = prompt("Promo title:", "Flash Sale");
  if (!title) return;
  const subtitle = prompt("Subtitle (e.g. 20% OFF):", "20% OFF") || "";
  const res = await apiPost('/promos', (() => {
    const fd = new FormData();
    fd.append('title', title);
    fd.append('subtitle', subtitle);
    return fd;
  })());
  if (res.success) {
    promoBanners = [{ id: res.banner.banner_id, title: res.banner.title, subtitle: res.banner.subtitle }];
    renderPromos();
    showToast("Promo banner saved");
  }
};

/* ---------------------------------------------------------
   MODAL OUTSIDE CLICK
--------------------------------------------------------- */
[document.getElementById("productModal"), document.getElementById("deleteModal")].forEach(modal => {
  modal.addEventListener("click", e => {
    if (e.target === modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    }
  });
});

/* ---------------------------------------------------------
   CATEGORY LOADER
--------------------------------------------------------- */
async function loadCategorySuggestions() {
  const names = await apiGet('/categories');
  const list = document.getElementById("categoryList");
  list.innerHTML = names.map(n => `<option value="${n}">`).join("");
  const filter = document.getElementById("categoryFilter");
  filter.innerHTML = '<option value="All">All</option>' + names.map(n => `<option value="${n}">${n}</option>`).join("");
}

// ESTEBAN — added: loads category suggestions from DB for datalist + filter (was hardcoded <select>)
function populateBrandFilter() {
  const brandFilter = document.getElementById("brandFilter");
  brandFilter.innerHTML = '<option value="All Brands">All Brands</option>' + allBrands.map(b => `<option value="${b}">${b}</option>`).join("");
}

// ESTEBAN — added: dynamically populates brand filter from loaded products (was hardcoded)
/* ---------------------------------------------------------
   INIT
-------------------------------------------------------- */
(async function init() {
  await loadCategorySuggestions();
  await loadProducts();
  await loadPromos();
})();
</script>

@endsection
