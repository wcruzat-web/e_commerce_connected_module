{{--
    resources/views/checkout.blade.php

    Single-file Laravel Blade view for the storefront checkout flow:
    Step 1: Cart  ->  Step 2: Checkout  ->  Step 3: Payment  ->  Step 4: Success

    Route (routes/web.php):
        Route::get('/checkout', function () {
            return view('checkout');
        })->name('checkout');

    Then visit: /checkout
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
  .fade-in { animation: fadeIn .18s ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(6px);} to { opacity: 1; transform: translateY(0);} }
  input::placeholder { color: #94a3b8; }
</style>
</head>
<body class="bg-slate-50 text-slate-800">

<!-- ============ TOP BAR ============ -->
<div class="bg-[#152a6e] text-white">
  <div class="max-w-7xl mx-auto px-6 py-2 flex items-center justify-between text-xs">
    <div>Free on orders over $99&nbsp; |&nbsp; Next-Day Delivery Available</div>
    <div class="flex items-center gap-3">
      <span>Admin Portal</span>
      <span class="text-white/30">|</span>
      <span class="flex items-center gap-1 text-sky-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24 11.36 11.36 0 003.57.57 1 1 0 011 1V20a1 1 0 01-1 1C10.61 21 3 13.39 3 4a1 1 0 011-1h3.5a1 1 0 011 1 11.36 11.36 0 00.57 3.57 1 1 0 01-.25 1.02l-2.2 2.2z"/></svg>
        09**********
      </span>
    </div>
  </div>
  <div class="border-t border-white/10">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <nav class="flex items-center gap-8 text-sm font-medium">
        <a href="#" class="nav-link hover:text-sky-300 transition">All Hardware</a>
        <a href="#" class="nav-link hover:text-sky-300 transition">Processors</a>
        <a href="#" class="nav-link hover:text-sky-300 transition">GPUs</a>
        <a href="#" class="nav-link hover:text-sky-300 transition">Motherboards</a>
        <a href="#" class="nav-link hover:text-sky-300 transition">Deals</a>
      </nav>
      <div class="flex items-center gap-4">
        <button id="searchIconBtn" class="hover:text-sky-300 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>
        <button id="cartIconBtn" class="relative hover:text-sky-300 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          <span id="cartBadge" class="absolute -top-2 -right-2 bg-sky-400 text-[#152a6e] text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">3</span>
        </button>
        <button id="userIconBtn" class="hover:text-sky-300 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============ MAIN ============ -->
<main class="max-w-7xl mx-auto px-6 py-6">

  <div class="text-xs text-slate-400 mb-4">
    <span class="hover:text-slate-600 cursor-pointer">Home</span> &nbsp;>&nbsp; <span class="text-slate-500">Shopping Cart</span>
  </div>

  <!-- Stepper -->
  <div class="flex items-center justify-center mb-8">
    <div id="stepper" class="flex items-center"></div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5">

    <!-- LEFT: step content -->
    <div id="stepContent"></div>

    <!-- RIGHT: sidebar -->
    <div class="flex flex-col gap-4">
      <div id="voucherBox" class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41L11 3.83A2 2 0 009.59 3.24L3.24 9.59A2 2 0 003.83 11l9.58 9.59a2 2 0 002.82 0l4.36-4.36a2 2 0 000-2.82z"/><circle cx="8" cy="8" r="1"/></svg>
          Voucher / Coupon
        </div>
        <div class="flex gap-2">
          <input id="voucherInput" type="text" placeholder="Enter code (SHOP20)" class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          <button id="applyVoucherBtn" class="px-4 py-2 text-sm font-medium bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">Apply</button>
        </div>
        <div id="voucherMsg" class="text-xs mt-2"></div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="font-semibold text-slate-800 text-sm mb-3">Order Summary</div>
        <div class="flex flex-col gap-2 text-sm">
          <div class="flex justify-between">
            <span class="text-slate-500">Items&nbsp; (<span id="summaryItemCount">3</span>)</span>
            <span class="font-medium" id="summaryItemsTotal">$2,715</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-500 flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              Shipping
            </span>
            <span class="font-medium text-emerald-500" id="summaryShipping">FREE</span>
          </div>
          <div class="flex justify-between" id="discountRow" style="display:none;">
            <span class="text-slate-500">Discount</span>
            <span class="font-medium text-emerald-500" id="summaryDiscount">-$0</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Tax (8%)</span>
            <span class="font-medium" id="summaryTax">$202</span>
          </div>
          <div class="border-t border-gray-100 my-1"></div>
          <div class="flex justify-between text-base">
            <span class="font-semibold text-slate-800">Grand Total</span>
            <span class="font-bold text-slate-900" id="summaryGrandTotal">$2,728</span>
          </div>
        </div>
        <div id="proceedBtnWrap" class="mt-4"></div>
        <div class="text-xs text-slate-400 mt-2 flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
          Free shipping on this order
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 z-50"></div>

<script>
/* ---------------------------------------------------------
   DATA
--------------------------------------------------------- */
let cartItems = [
  {
    id: 1, name: "NVIDIA GeForce RTX 4090 Founders Edition", brand: "NVIDIA", category: "Graphics Cards",
    sku: "NV-RTX4090-FE-24G", price: 1599, qty: 1, stock: "In Stock",
    icon: "gpu"
  },
  {
    id: 2, name: "Intel Core i9-14900K Processor", brand: "Intel", category: "Processors",
    sku: "IN-14900K-BOX", price: 549, qty: 1, stock: "In Stock",
    icon: "cpu"
  },
  {
    id: 3, name: "Corsair Vengeance DDR5-6400 32GB Kit", brand: "Corsair", category: "Memory",
    sku: "CO-DDR5-6400-32", price: 567, qty: 1, stock: "Only 3 left",
    icon: "ram"
  },
];

const shippingMethods = [
  { id: "standard", label: "Standard Shipping", detail: "5-7 Business days", price: 0 },
  { id: "expedited", label: "Standard Shipping", detail: "2-3 Business days", price: 29 },
  { id: "next_day", label: "Standard Shipping", detail: "Next Business day", price: 79 },
];

let checkoutDetails = {
  firstName: "Alex", lastName: "Morgan", email: "alexmorgan@gmail.com", phone: "+1 (555) 000-0000",
  street: "123 Tech Boulevard, Suite 400", city: "San Francisco", state: "CA", zip: "94105",
  shippingMethodId: "standard",
};

let paymentDetails = {
  method: "visa",
  cardholder: "Alex Morgan", cardNumber: "0123 4567 8901 2345", expiry: "", cvv: "",
  gcashName: "Alex Morgan", gcashNumber: "+63 1234 456 7890",
};

let voucherApplied = null; // { code, discountPct }
let orderInfo = null;
let currentStep = 1; // 1 Cart, 2 Checkout, 3 Payment, 4 Success

const steps = [
  { n: 1, label: "Cart" },
  { n: 2, label: "Checkout" },
  { n: 3, label: "Payment" },
  { n: 4, label: "Success" },
];

/* ---------------------------------------------------------
   HELPERS
--------------------------------------------------------- */
function money(n) {
  return "$" + n.toLocaleString(undefined, { maximumFractionDigits: 0 });
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

function calcTotals() {
  const itemsTotal = cartItems.reduce((sum, i) => sum + i.price * i.qty, 0);
  const shippingMethod = shippingMethods.find(s => s.id === checkoutDetails.shippingMethodId) || shippingMethods[0];
  let shippingCost = itemsTotal >= 99 && shippingMethod.id === "standard" ? 0 : shippingMethod.price;
  const discount = voucherApplied ? Math.round(itemsTotal * (voucherApplied.discountPct / 100)) : 0;
  const taxable = itemsTotal - discount;
  const tax = Math.round(taxable * 0.08);
  const grandTotal = taxable + shippingCost + tax;
  return { itemsTotal, shippingCost, discount, tax, grandTotal, shippingMethod };
}

function renderSummary() {
  const t = calcTotals();
  document.getElementById("summaryItemCount").textContent = cartItems.reduce((s, i) => s + i.qty, 0);
  document.getElementById("summaryItemsTotal").textContent = money(t.itemsTotal);
  document.getElementById("summaryShipping").textContent = t.shippingCost === 0 ? "FREE" : money(t.shippingCost);
  document.getElementById("summaryShipping").className = "font-medium " + (t.shippingCost === 0 ? "text-emerald-500" : "text-slate-700");
  document.getElementById("summaryTax").textContent = money(t.tax);
  document.getElementById("summaryGrandTotal").textContent = money(t.grandTotal);

  const discountRow = document.getElementById("discountRow");
  if (t.discount > 0) {
    discountRow.style.display = "flex";
    document.getElementById("summaryDiscount").textContent = "-" + money(t.discount);
  } else {
    discountRow.style.display = "none";
  }

  // proceed button only on cart step
  const wrap = document.getElementById("proceedBtnWrap");
  if (currentStep === 1) {
    wrap.innerHTML = `
      <button id="proceedCheckoutBtn" class="w-full px-4 py-2.5 text-sm font-semibold bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition flex items-center justify-center gap-1">
        Proceed to Checkout
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>`;
    document.getElementById("proceedCheckoutBtn").onclick = () => goToStep(2);
  } else {
    wrap.innerHTML = "";
  }

  // cart badge in header
  document.getElementById("cartBadge").textContent = cartItems.reduce((s, i) => s + i.qty, 0);

  // voucher box hidden on success step
  document.getElementById("voucherBox").style.display = currentStep === 4 ? "none" : "block";
}

/* ---------------------------------------------------------
   STEPPER
--------------------------------------------------------- */
function renderStepper() {
  const el = document.getElementById("stepper");
  el.innerHTML = steps.map((s, idx) => {
    const isDone = s.n < currentStep;
    const isActive = s.n === currentStep;
    const circleCls = isDone
      ? "bg-emerald-500 text-white"
      : isActive
        ? "bg-[#152a6e] text-white"
        : "bg-gray-200 text-gray-500";
    const labelCls = isDone ? "text-emerald-600" : isActive ? "text-[#152a6e] font-semibold" : "text-gray-400";
    const circleInner = isDone
      ? `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>`
      : s.n;
    const clickable = isDone ? "cursor-pointer" : "cursor-default";

    let connector = "";
    if (idx < steps.length - 1) {
      const lineDone = s.n < currentStep;
      connector = `<div class="w-20 sm:w-28 h-0.5 mx-2 ${lineDone ? "bg-emerald-500" : "bg-gray-200"}"></div>`;
    }

    return `
      <div class="flex items-center">
        <div class="flex flex-col items-center gap-1.5">
          <div data-step="${s.n}" class="step-circle w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold ${circleCls} ${clickable}">${circleInner}</div>
          <div class="text-xs ${labelCls}">${s.label}</div>
        </div>
        ${connector}
      </div>
    `;
  }).join("");

  document.querySelectorAll(".step-circle").forEach(el => {
    const n = Number(el.dataset.step);
    if (n < currentStep) {
      el.onclick = () => goToStep(n);
    }
  });
}

/* ---------------------------------------------------------
   STEP 1: CART
--------------------------------------------------------- */
function iconSvg(type) {
  const icons = {
    gpu: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="10" rx="2"/><circle cx="7" cy="12" r="2"/><circle cx="13" cy="12" r="2"/><line x1="18" y1="9" x2="18" y2="15"/></svg>`,
    cpu: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="6" y="6" width="12" height="12" rx="1"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/></svg>`,
    ram: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="8" width="18" height="8" rx="1"/><line x1="6" y1="8" x2="6" y2="4"/><line x1="10" y1="8" x2="10" y2="4"/><line x1="14" y1="8" x2="14" y2="4"/><line x1="18" y1="8" x2="18" y2="4"/></svg>`,
  };
  return icons[type] || icons.gpu;
}

function stockBadge(stock) {
  if (stock === "In Stock") return `<span class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600">In Stock</span>`;
  if (stock.toLowerCase().includes("left")) return `<span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-600">${stock}</span>`;
  return `<span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-500">${stock}</span>`;
}

function renderCartStep() {
  const content = document.getElementById("stepContent");
  content.innerHTML = `
    <div class="bg-white rounded-xl border border-gray-200 p-4 fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          Cart Items
          <span class="text-xs font-medium bg-sky-100 text-sky-600 px-2 py-0.5 rounded-full" id="cartCountPill">${cartItems.length} items</span>
        </div>
        <button id="continueShoppingBtn" class="text-xs font-medium text-sky-500 hover:text-sky-600 flex items-center gap-1">
          Continue Shopping
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>
      <div id="cartList" class="flex flex-col divide-y divide-gray-100"></div>
    </div>
  `;

  document.getElementById("continueShoppingBtn").onclick = () => showToast("This is a demo storefront — no catalog page here.");

  renderCartList();
}

function renderCartList() {
  const list = document.getElementById("cartList");
  if (cartItems.length === 0) {
    list.innerHTML = `<div class="py-10 text-center text-sm text-slate-400">Your cart is empty.</div>`;
  } else {
    list.innerHTML = cartItems.map(item => `
      <div class="flex items-center gap-4 py-4 fade-in">
        <div class="w-16 h-16 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
          ${iconSvg(item.icon)}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">${item.brand}</span>
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">${item.category}</span>
          </div>
          <div class="text-sm font-medium text-slate-800 truncate">${item.name}</div>
          <div class="text-xs text-slate-400">SKU: ${item.sku}</div>
          <div class="mt-1">${stockBadge(item.stock)}</div>
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
              <button class="qty-decrease w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-gray-50" data-id="${item.id}">−</button>
              <span class="w-8 text-center text-sm">${item.qty}</span>
              <button class="qty-increase w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-gray-50" data-id="${item.id}">+</button>
            </div>
            <button class="remove-item text-xs font-medium text-red-400 hover:text-red-500 flex items-center gap-1" data-id="${item.id}">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
              Remove
            </button>
          </div>
        </div>
        <div class="text-right shrink-0">
          <div class="font-semibold text-slate-800">${money(item.price * item.qty)}</div>
          <div class="text-xs text-slate-400">${money(item.price)} each</div>
        </div>
      </div>
    `).join("");
  }

  document.getElementById("cartCountPill").textContent = `${cartItems.length} items`;

  document.querySelectorAll(".qty-increase").forEach(btn => {
    btn.onclick = () => {
      const item = cartItems.find(i => i.id === Number(btn.dataset.id));
      item.qty++;
      renderCartList();
      renderSummary();
    };
  });
  document.querySelectorAll(".qty-decrease").forEach(btn => {
    btn.onclick = () => {
      const item = cartItems.find(i => i.id === Number(btn.dataset.id));
      if (item.qty > 1) item.qty--;
      renderCartList();
      renderSummary();
    };
  });
  document.querySelectorAll(".remove-item").forEach(btn => {
    btn.onclick = () => {
      const item = cartItems.find(i => i.id === Number(btn.dataset.id));
      cartItems = cartItems.filter(i => i.id !== Number(btn.dataset.id));
      showToast(`${item.name} removed from cart`);
      renderCartList();
      renderSummary();
    };
  });
}

/* ---------------------------------------------------------
   STEP 2: CHECKOUT
--------------------------------------------------------- */
function renderCheckoutStep() {
  const content = document.getElementById("stepContent");
  content.innerHTML = `
    <div class="bg-white rounded-xl border border-gray-200 p-5 fade-in">
      <div class="flex items-center gap-2 text-base font-semibold text-slate-800 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        Checkout Details
      </div>
      <form id="checkoutForm" class="flex flex-col gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="text-xs font-medium text-slate-500">First Name</label>
            <input required id="cFirstName" type="text" value="${checkoutDetails.firstName}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">Last Name</label>
            <input required id="cLastName" type="text" value="${checkoutDetails.lastName}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">Email</label>
            <input required id="cEmail" type="email" value="${checkoutDetails.email}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">Phone</label>
            <input required id="cPhone" type="text" value="${checkoutDetails.phone}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Street Address</label>
          <input required id="cStreet" type="text" value="${checkoutDetails.street}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="text-xs font-medium text-slate-500">City</label>
            <input required id="cCity" type="text" value="${checkoutDetails.city}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">State</label>
            <input required id="cState" type="text" value="${checkoutDetails.state}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">ZIP Code</label>
            <input required id="cZip" type="text" value="${checkoutDetails.zip}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
        </div>

        <div>
          <label class="text-xs font-medium text-slate-500 mb-2 block">Shipping Method</label>
          <div id="shippingMethodList" class="flex flex-col gap-2"></div>
        </div>

        <button type="submit" class="w-full mt-2 px-4 py-3 text-sm font-semibold bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition flex items-center justify-center gap-1">
          Continue to Payment
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </form>
    </div>
  `;

  renderShippingMethods();

  document.getElementById("checkoutForm").addEventListener("submit", e => {
    e.preventDefault();
    checkoutDetails.firstName = document.getElementById("cFirstName").value.trim();
    checkoutDetails.lastName = document.getElementById("cLastName").value.trim();
    checkoutDetails.email = document.getElementById("cEmail").value.trim();
    checkoutDetails.phone = document.getElementById("cPhone").value.trim();
    checkoutDetails.street = document.getElementById("cStreet").value.trim();
    checkoutDetails.city = document.getElementById("cCity").value.trim();
    checkoutDetails.state = document.getElementById("cState").value.trim();
    checkoutDetails.zip = document.getElementById("cZip").value.trim();
    goToStep(3);
  });
}

function renderShippingMethods() {
  const list = document.getElementById("shippingMethodList");
  const itemsTotal = cartItems.reduce((sum, i) => sum + i.price * i.qty, 0);
  const icons = {
    standard: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
    expedited: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 8L18 3H6L3 8"/><path d="M3 8h18v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/><path d="M9 16v3"/><path d="M15 16v3"/></svg>`,
    next_day: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>`,
  };

  list.innerHTML = shippingMethods.map(m => {
    const isFree = m.id === "standard" && itemsTotal >= 99;
    const priceLabel = isFree ? "FREE" : money(m.price);
    const checked = checkoutDetails.shippingMethodId === m.id;
    return `
      <label class="flex items-center justify-between border ${checked ? "border-sky-400 ring-1 ring-sky-200" : "border-gray-200"} rounded-lg px-4 py-3 cursor-pointer hover:border-sky-300 transition">
        <div class="flex items-center gap-3">
          <input type="radio" name="shippingMethod" value="${m.id}" ${checked ? "checked" : ""} class="shipping-radio accent-sky-500">
          <span class="text-slate-400">${icons[m.id]}</span>
          <div>
            <div class="text-sm font-medium text-slate-800">${m.label}</div>
            <div class="text-xs text-slate-400">${m.detail}</div>
          </div>
        </div>
        <div class="text-sm font-semibold ${isFree ? "text-emerald-500" : "text-slate-700"}">${priceLabel}</div>
      </label>
    `;
  }).join("");

  document.querySelectorAll(".shipping-radio").forEach(radio => {
    radio.onchange = () => {
      checkoutDetails.shippingMethodId = radio.value;
      renderShippingMethods();
      renderSummary();
    };
  });
}

/* ---------------------------------------------------------
   STEP 3: PAYMENT
--------------------------------------------------------- */
function renderPaymentStep() {
  const content = document.getElementById("stepContent");
  const t = calcTotals();
  content.innerHTML = `
    <div class="bg-white rounded-xl border border-gray-200 p-5 fade-in">
      <div class="flex items-center gap-2 text-base font-semibold text-slate-800 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Payment Details
      </div>

      <div class="grid grid-cols-3 gap-2 mb-5" id="paymentTabs"></div>

      <div id="paymentFields"></div>

      <div class="flex items-center gap-3 mt-5">
        <button id="paymentBackBtn" type="button" class="px-4 py-3 text-sm font-medium border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Back
        </button>
        <button id="placeOrderBtn" class="flex-1 px-4 py-3 text-sm font-semibold bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition" data-total="${t.grandTotal}">
          Place Order — <span id="placeOrderAmount">${money(t.grandTotal)}</span>
        </button>
      </div>
    </div>
  `;

  renderPaymentTabs();
  renderPaymentFields();

  document.getElementById("paymentBackBtn").onclick = () => goToStep(2);
  document.getElementById("placeOrderBtn").onclick = handlePlaceOrder;
}

function renderPaymentTabs() {
  const tabs = [
    { id: "visa", label: "Visa" },
    { id: "mastercard", label: "Mastercard" },
    { id: "gcash", label: "G-Cash" },
  ];
  const el = document.getElementById("paymentTabs");
  el.innerHTML = tabs.map(t => `
    <button data-method="${t.id}" class="payment-tab py-2.5 text-sm font-medium rounded-lg border transition ${paymentDetails.method === t.id ? "border-sky-400 text-sky-600 bg-sky-50" : "border-gray-200 text-slate-500 hover:bg-gray-50"}">
      ${t.label}
    </button>
  `).join("");

  document.querySelectorAll(".payment-tab").forEach(btn => {
    btn.onclick = () => {
      paymentDetails.method = btn.dataset.method;
      renderPaymentTabs();
      renderPaymentFields();
    };
  });
}

function renderPaymentFields() {
  const el = document.getElementById("paymentFields");
  if (paymentDetails.method === "gcash") {
    el.innerHTML = `
      <div class="flex flex-col gap-4">
        <div>
          <label class="text-xs font-medium text-slate-500">GCash Name</label>
          <input id="pGcashName" type="text" value="${paymentDetails.gcashName}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">GCash Number</label>
          <input id="pGcashNumber" type="text" value="${paymentDetails.gcashNumber}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
      </div>
    `;
  } else {
    el.innerHTML = `
      <div class="flex flex-col gap-4">
        <div>
          <label class="text-xs font-medium text-slate-500">Cardholder Name</label>
          <input id="pCardholder" type="text" value="${paymentDetails.cardholder}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
        <div>
          <label class="text-xs font-medium text-slate-500">Card Number</label>
          <input id="pCardNumber" type="text" value="${paymentDetails.cardNumber}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs font-medium text-slate-500">Expiry Date</label>
            <input id="pExpiry" type="text" placeholder="MM/YY" value="${paymentDetails.expiry}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
          <div>
            <label class="text-xs font-medium text-slate-500">CVV</label>
            <input id="pCvv" type="password" placeholder="•••" value="${paymentDetails.cvv}" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
          </div>
        </div>
      </div>
    `;
  }
}

function readPaymentFieldsIntoState() {
  if (paymentDetails.method === "gcash") {
    paymentDetails.gcashName = document.getElementById("pGcashName")?.value.trim() || paymentDetails.gcashName;
    paymentDetails.gcashNumber = document.getElementById("pGcashNumber")?.value.trim() || paymentDetails.gcashNumber;
  } else {
    paymentDetails.cardholder = document.getElementById("pCardholder")?.value.trim() || paymentDetails.cardholder;
    paymentDetails.cardNumber = document.getElementById("pCardNumber")?.value.trim() || paymentDetails.cardNumber;
    paymentDetails.expiry = document.getElementById("pExpiry")?.value.trim() || paymentDetails.expiry;
    paymentDetails.cvv = document.getElementById("pCvv")?.value.trim() || paymentDetails.cvv;
  }
}

function handlePlaceOrder() {
  readPaymentFieldsIntoState();

  if (paymentDetails.method === "gcash") {
    if (!paymentDetails.gcashName || !paymentDetails.gcashNumber) {
      showToast("Please fill in your GCash details");
      return;
    }
  } else {
    if (!paymentDetails.cardholder || !paymentDetails.cardNumber || !paymentDetails.expiry || !paymentDetails.cvv) {
      showToast("Please fill in all card details");
      return;
    }
  }

  const t = calcTotals();
  const orderNum = "NX-2024-" + Math.floor(10000 + Math.random() * 89999);
  const trackingNum = "NX" + Math.floor(10000000 + Math.random() * 89999999);

  const today = new Date();
  const start = new Date(today); start.setDate(today.getDate() + 3);
  const end = new Date(today); end.setDate(today.getDate() + 5);
  const fmt = d => d.toLocaleDateString("en-US", { month: "long", day: "numeric" }) ;
  const deliveryRange = `${fmt(start)}-${fmt(end)}, ${end.getFullYear()}`;

  orderInfo = {
    orderNum, trackingNum, deliveryRange, total: t.grandTotal,
    email: checkoutDetails.email,
  };

  goToStep(4);
}

/* ---------------------------------------------------------
   STEP 4: SUCCESS
--------------------------------------------------------- */
function renderSuccessStep() {
  const content = document.getElementById("stepContent");
  const o = orderInfo || { orderNum: "NX-2024-00000", trackingNum: "NX00000000", deliveryRange: "—", total: calcTotals().grandTotal, email: checkoutDetails.email };

  content.innerHTML = `
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center fade-in">
      <div class="w-16 h-16 rounded-full bg-sky-500 flex items-center justify-center mx-auto mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h2 class="text-xl font-bold text-slate-900 mb-1">Order Confirmed!</h2>
      <p class="text-sm text-slate-400 mb-1">Order #${o.orderNum}</p>
      <p class="text-sm text-slate-400 mb-6">A confirmation email has been sent to ${o.email || "your email address"}. Your order will be processed within 24 hours.</p>

      <div class="bg-slate-50 rounded-xl p-4 grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 text-left">
        <div>
          <div class="text-xs text-slate-400 mb-1">Order Total</div>
          <div class="font-semibold text-slate-800">${money(o.total)}</div>
        </div>
        <div>
          <div class="text-xs text-slate-400 mb-1">Est. Delivery</div>
          <div class="font-semibold text-slate-800">${o.deliveryRange}</div>
        </div>
        <div>
          <div class="text-xs text-slate-400 mb-1">Tracking</div>
          <div class="font-semibold text-sky-500">${o.trackingNum}</div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-3">
        <button id="trackOrderBtn" class="flex-1 px-4 py-3 text-sm font-semibold bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">Track Order &gt;</button>
        <button id="continueShoppingSuccessBtn" class="flex-1 px-4 py-3 text-sm font-semibold border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition">Continue Shopping</button>
      </div>
    </div>
  `;

  document.getElementById("trackOrderBtn").onclick = () => showToast(`Tracking order ${o.trackingNum}...`);
  document.getElementById("continueShoppingSuccessBtn").onclick = () => resetFlow();
}

/* ---------------------------------------------------------
   VOUCHER
--------------------------------------------------------- */
document.getElementById("applyVoucherBtn").addEventListener("click", () => {
  const code = document.getElementById("voucherInput").value.trim().toUpperCase();
  const msg = document.getElementById("voucherMsg");
  if (!code) {
    msg.textContent = "Please enter a code.";
    msg.className = "text-xs mt-2 text-red-500";
    return;
  }
  if (code === "SHOP20") {
    voucherApplied = { code, discountPct: 20 };
    msg.textContent = "SHOP20 applied — 20% off items!";
    msg.className = "text-xs mt-2 text-emerald-500";
    showToast("Voucher applied successfully");
  } else {
    voucherApplied = null;
    msg.textContent = "Invalid or expired code.";
    msg.className = "text-xs mt-2 text-red-500";
  }
  renderSummary();
});

/* ---------------------------------------------------------
   NAVIGATION
--------------------------------------------------------- */
function goToStep(n) {
  currentStep = n;
  renderStepper();
  if (n === 1) renderCartStep();
  else if (n === 2) renderCheckoutStep();
  else if (n === 3) renderPaymentStep();
  else if (n === 4) renderSuccessStep();
  renderSummary();
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function resetFlow() {
  cartItems = [
    { id: 1, name: "NVIDIA GeForce RTX 4090 Founders Edition", brand: "NVIDIA", category: "Graphics Cards", sku: "NV-RTX4090-FE-24G", price: 1599, qty: 1, stock: "In Stock", icon: "gpu" },
    { id: 2, name: "Intel Core i9-14900K Processor", brand: "Intel", category: "Processors", sku: "IN-14900K-BOX", price: 549, qty: 1, stock: "In Stock", icon: "cpu" },
    { id: 3, name: "Corsair Vengeance DDR5-6400 32GB Kit", brand: "Corsair", category: "Memory", sku: "CO-DDR5-6400-32", price: 567, qty: 1, stock: "Only 3 left", icon: "ram" },
  ];
  voucherApplied = null;
  orderInfo = null;
  document.getElementById("voucherInput").value = "";
  document.getElementById("voucherMsg").textContent = "";
  goToStep(1);
}

/* ---------------------------------------------------------
   MISC UI
--------------------------------------------------------- */
document.getElementById("searchIconBtn").onclick = () => showToast("Search is not wired up in this demo");
document.getElementById("cartIconBtn").onclick = () => goToStep(1);
document.getElementById("userIconBtn").onclick = () => showToast("Account menu is not wired up in this demo");
document.querySelectorAll(".nav-link").forEach(a => {
  a.addEventListener("click", e => {
    e.preventDefault();
    showToast(`"${a.textContent}" category page is not built in this demo`);
  });
});

/* ---------------------------------------------------------
   INIT
--------------------------------------------------------- */
goToStep(1);
</script>

</body>
</html>
