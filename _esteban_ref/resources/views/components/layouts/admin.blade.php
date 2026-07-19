<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Product Display Management</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"></script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
  .fade-in { animation: fadeIn .15s ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px);} to { opacity: 1; transform: translateY(0);} }
  .modal-in { animation: modalIn .18s ease-out; }
  @keyframes modalIn { from { opacity: 0; transform: scale(.96);} to { opacity: 1; transform: scale(1);} }
</style>
</head>
<body class="bg-gray-50 text-slate-800">

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-56 shrink-0 bg-[#152a6e] text-white flex flex-col justify-between">
    <div>
      <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="w-9 h-9 rounded-full bg-slate-300"></div>
        <div>
          <div class="font-semibold text-sm leading-tight">BusinessName's</div>
          <div class="text-xs text-blue-200 leading-tight">Admin</div>
        </div>
      </div>

      <nav class="mt-4 flex flex-col gap-1 px-3 text-sm">
        <button data-page="dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 hover:bg-white/10 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          Dashboard
        </button>
        <button data-page="product" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/10 text-white font-medium transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
          Product
        </button>
        <button data-page="inventory" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 hover:bg-white/10 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
          Inventory
        </button>
      </nav>
    </div>

    <div class="px-3 pb-5">
      <button id="signOutBtn" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 hover:bg-white/10 transition text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Sign Out
      </button>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 flex flex-col">

    <!-- Topbar -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
      <div class="flex items-center gap-2 text-sm text-teal-500 font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 2l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 22l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
        ERP Sync Active
        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
      </div>

      <div class="flex items-center gap-5">
        <button id="notifBtn" class="relative text-slate-500 hover:text-slate-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
          <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <div class="h-6 w-px bg-gray-200"></div>
        <div class="flex items-center gap-3">
          <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=JohnDoe" class="w-9 h-9 rounded-full bg-slate-100" alt="avatar">
          <div class="text-sm">
            <div class="font-medium text-slate-800 leading-tight">John Doe</div>
            <div class="text-xs text-slate-400 leading-tight">Super Admin</div>
          </div>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-6">
      {{ $slot }}
    </main>
  </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 z-50"></div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-40 p-4">
  <div class="modal-in bg-white rounded-xl shadow-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 id="modalTitle" class="text-lg font-bold text-slate-900">Add Product</h2>
      <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="productForm" class="flex flex-col gap-3" enctype="multipart/form-data">
      <input type="hidden" id="productId">
      <div>
        <label class="text-xs font-medium text-slate-500">Product Name</label>
        <input required id="pName" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="e.g. NVIDIA RTX 4090 Founder Edition">
      </div>
      <div>
        <label class="text-xs font-medium text-slate-500">Brand</label>
        <input required id="pBrand" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="e.g. NVIDIA">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs font-medium text-slate-500">SKU</label>
          <input required id="pSku" type="text" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="NV-4090-FE">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Category</label>
          <select id="pCategory" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
            <option value="GPU">GPU</option>
            <option value="CPU">CPU</option>
            <option value="Motherboard">Motherboard</option>
            <option value="Memory">Memory</option>
            <option value="Cooling">Cooling</option>
          </select>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs font-medium text-slate-500">Price ($)</label>
          <input required id="pPrice" type="number" step="0.01" min="0" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="1599">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Stock Qty</label>
          <input required id="pStock" type="number" min="0" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="2">
        </div>
      </div>
      <div>
        <label class="text-xs font-medium text-slate-500">Product Image</label>
        <input id="pImage" type="file" accept="image/*" class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
        <div id="imagePreview" class="mt-2 hidden">
          <img id="imagePreviewImg" src="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
        </div>
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

<script>
/* ---------------------------------------------------------
   DATA - loaded from database via AJAX
--------------------------------------------------------- */
let products = [];
let promoBanners = [];

const categoryColors = {
  GPU: "bg-sky-100 text-sky-600",
  CPU: "bg-blue-100 text-blue-600",
  Motherboard: "bg-indigo-100 text-indigo-600",
  Memory: "bg-emerald-100 text-emerald-600",
  Cooling: "bg-cyan-100 text-cyan-600",
};

const categoryToChartLabel = {
  GPU: "GPU", CPU: "CPU", Motherboard: "MB", Memory: "RAM", Cooling: "Cooling",
};

const categoryMaxStock = {
  GPU: 50, CPU: 100, Motherboard: 30, Memory: 80, Cooling: 40,
};

/* ---------------------------------------------------------
   API HELPERS
--------------------------------------------------------- */
const API_BASE = '';
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
async function loadProducts() {
  const params = new URLSearchParams();
  if (filters.search) params.set('search', filters.search);
  if (filters.category !== 'All') params.set('category', filters.category);
  if (filters.brand !== 'All Brands') params.set('brand', filters.brand);
  if (filters.stock !== 'All') params.set('stock', filters.stock);

  const data = await apiGet('/api/products?' + params.toString());
  products = data.map(p => ({
    id: p.product_id,
    name: p.product_name,
    brand: p.brand,
    sku: p.sku,
    category: p.category,
    price: parseFloat(p.price),
    stock: p.stock,
    featured: p.is_featured,
    image: p.product_image,
    image_url: p.product_image ? '/storage/products/' + p.product_image : null,
  }));
  renderTable();
  renderFeatured();
}

async function loadPromos() {
  const data = await apiGet('/api/promos');
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

function attachRowListeners() {
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
      await apiDelete('/api/promos/' + btn.dataset.id);
      promoBanners = promoBanners.filter(b => b.id !== Number(btn.dataset.id));
      renderPromos();
      showToast("Promo banner removed");
    };
  });
}

/* ---------------------------------------------------------
   PRODUCT MODAL
--------------------------------------------------------- */
function openAddModal() {
  editingId = null;
  document.getElementById("modalTitle").textContent = "Add Product";
  document.getElementById("productForm").reset();
  document.getElementById("imagePreview").classList.add("hidden");
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

  if (p.image_url) {
    document.getElementById("imagePreview").classList.remove("hidden");
    document.getElementById("imagePreviewImg").src = p.image_url;
  } else {
    document.getElementById("imagePreview").classList.add("hidden");
  }

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

document.getElementById("productForm").addEventListener("submit", async e => {
  e.preventDefault();
  const saveBtn = document.getElementById("saveProductBtn");
  saveBtn.disabled = true;
  saveBtn.textContent = "Saving...";

  const fd = new FormData();
  fd.append('product_name', document.getElementById("pName").value.trim());
  fd.append('brand', document.getElementById("pBrand").value.trim());
  fd.append('sku', document.getElementById("pSku").value.trim());
  fd.append('category', document.getElementById("pCategory").value);
  fd.append('price', parseFloat(document.getElementById("pPrice").value));
  fd.append('stock', parseInt(document.getElementById("pStock").value, 10));

  const imageInput = document.getElementById("pImage");
  if (imageInput.files.length > 0) {
    fd.append('product_image', imageInput.files[0]);
  }

  try {
    if (editingId) {
      fd.append('_method', 'PUT');
      await apiPut('/api/products/' + editingId, fd);
      showToast("Product updated successfully");
    } else {
      await apiPost('/api/products', fd);
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
  await apiDelete('/api/products/' + deletingId);
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
document.getElementById("notifBtn").onclick = () => showToast("You have 1 new notification");
document.getElementById("signOutBtn").onclick = () => showToast("Signed out (demo only)");
document.getElementById("addPromoBtn").onclick = async () => {
  const title = prompt("Promo title:", "Flash Sale");
  if (!title) return;
  const subtitle = prompt("Subtitle (e.g. 20% OFF):", "20% OFF") || "";
  const res = await apiPost('/api/promos', (() => {
    const fd = new FormData();
    fd.append('title', title);
    fd.append('subtitle', subtitle);
    return fd;
  })());
  if (res.success) {
    promoBanners.unshift({ id: res.banner.banner_id, title: res.banner.title, subtitle: res.banner.subtitle });
    renderPromos();
    showToast("Promo banner added");
  }
};

/* ---------------------------------------------------------
   NAVIGATION
--------------------------------------------------------- */
document.querySelectorAll(".nav-item").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".nav-item").forEach(b => {
      b.classList.remove("bg-white/10", "text-white", "font-medium");
      b.classList.add("text-blue-100");
    });
    btn.classList.add("bg-white/10", "text-white", "font-medium");
    btn.classList.remove("text-blue-100");
    goToPage(btn.dataset.page);
  });
});

[document.getElementById("productModal"), document.getElementById("deleteModal")].forEach(modal => {
  modal.addEventListener("click", e => {
    if (e.target === modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    }
  });
});

/* ---------------------------------------------------------
   INVENTORY PAGE
--------------------------------------------------------- */
async function renderInventoryStats() {
  const data = await apiGet('/api/inventory/stats');
  document.getElementById("statTotalProduct").textContent = data.totalProduct.toLocaleString();
  document.getElementById("statAvailableStock").textContent = data.availableStock.toLocaleString();
  document.getElementById("statLowStock").textContent = data.lowStockProduct;
  document.getElementById("statOutOfStock").textContent = data.outOfStock;

  window._categoryStockData = data.categoryStock;
  window._lowStockAlerts = data.lowStockAlerts;
  renderCategoryChart();
  renderLowStockList();
}

async function renderRevenueChart() {
  const revenueData = await apiGet('/api/revenue');
  window._revenueData = revenueData;

  const svg = document.getElementById("revenueChart");
  const tooltip = document.getElementById("revenueTooltip");
  const tooltipMonth = document.getElementById("revenueTooltipMonth");
  const tooltipValue = document.getElementById("revenueTooltipValue");
  const W = 600, H = 220, padL = 45, padB = 30, padT = 15, padR = 15;
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
    tooltipValue.textContent = "$" + p.value.toLocaleString();
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

function renderCategoryChart() {
  const catData = window._categoryStockData || [];
  const svg = document.getElementById("categoryChart");
  const W = 320, H = 220, padL = 55, padR = 15, padT = 5, padB = 20;
  const chartW = W - padL - padR, chartH = H - padT - padB;
  const maxVal = 2400;
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

  const xTicks = [0, 600, 1200, 1800, 2400];
  let axisLabels = xTicks.map(v => {
    const x = padL + (v / maxVal) * chartW;
    return `<text x="${x}" y="${H - 4}" font-size="9" fill="#94a3b8" text-anchor="middle">${v}</text>`;
  }).join("");

  svg.innerHTML = bars + axisLabels;
}

async function renderWarehouseList() {
  const warehouses = await apiGet('/api/inventory/warehouses');
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

async function renderInventoryPage() {
  await renderInventoryStats();
  await renderRevenueChart();
  await renderWarehouseList();
}

document.getElementById("forcedSyncBtn").addEventListener("click", async function () {
  const btn = this;
  btn.disabled = true;
  const originalText = btn.textContent;
  btn.textContent = "Syncing...";
  btn.classList.add("opacity-70");

  await apiPost('/api/inventory/sync', new FormData());
  document.getElementById("lastSyncText").textContent = "Just now";
  await renderWarehouseList();
  btn.disabled = false;
  btn.textContent = originalText;
  btn.classList.remove("opacity-70");
  showToast("All warehouses forced-synced successfully");
});

document.getElementById("exportReportBtn").addEventListener("click", async () => {
  const stats = await apiGet('/api/inventory/stats');
  const warehouses = await apiGet('/api/inventory/warehouses');

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
   PAGE NAVIGATION
--------------------------------------------------------- */
function goToPage(page) {
  document.getElementById("page-product").classList.add("hidden");
  document.getElementById("page-inventory").classList.add("hidden");

  if (page === "product") {
    document.getElementById("page-product").classList.remove("hidden");
  } else if (page === "inventory") {
    document.getElementById("page-inventory").classList.remove("hidden");
    renderInventoryPage();
  } else {
    document.getElementById("page-inventory").classList.remove("hidden");
    renderInventoryPage();
  }
}

/* ---------------------------------------------------------
   INIT - Load data from database
--------------------------------------------------------- */
(async function init() {
  await loadProducts();
  await loadPromos();
  goToPage("product");
})();
</script>

</body>
</html>
