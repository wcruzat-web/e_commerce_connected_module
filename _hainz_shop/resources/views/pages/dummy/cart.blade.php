<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - NexaStore PC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50" x-data="storeState()">

    @include('components.header.header')

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8" style="font-family: 'Outfit', sans-serif;">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-400 mb-6">
            <a href="/shop" class="hover:text-gray-600">Home</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-700 font-medium">Shopping Cart</span>
        </nav>

        {{-- Checkout Stepper --}}
        <div class="flex items-center justify-center mb-10">
            <template x-for="(step, index) in stepStates" :key="step.key">
                <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1 max-w-[160px]' : ''">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold"
                             :class="step.state === 'done' ? 'bg-green-700 text-white' : (step.state === 'active' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-500')">
                            <template x-if="step.state === 'done'">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </template>
                            <template x-if="step.state !== 'done'">
                                <span x-text="index + 1"></span>
                            </template>
                        </div>
                        <span class="text-xs mt-2" :class="step.state === 'upcoming' ? 'text-gray-400' : 'text-gray-900 font-medium'" x-text="step.label"></span>
                    </div>
                    <template x-if="index < steps.length - 1">
                        <div class="flex-1 h-0.5 mx-2 mb-5" :class="step.state === 'done' ? 'bg-green-700' : 'bg-gray-200'"></div>
                    </template>
                </div>
            </template>
        </div>

        <template x-if="cartItems.length === 0">
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <p class="text-sm text-gray-400">Your cart is empty.</p>
                    <a href="/shop" class="inline-block mt-3 text-sm font-medium text-cyan-500 hover:text-cyan-600">Start Shopping</a>
                </div>
            </div>
        </template>

        <template x-if="cartItems.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            <h2 class="text-sm font-semibold text-gray-900">Cart Items</h2>
                            <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full" x-text="cartItems.length + (cartItems.length === 1 ? ' item' : ' items')"></span>
                        </div>
                        <a href="/shop" class="flex items-center gap-1 text-xs font-medium text-cyan-500 hover:text-cyan-600">
                            Continue Shopping
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <template x-for="(item, idx) in cartItems" :key="item.id">
                            <div class="flex flex-col sm:flex-row items-start gap-4 py-4" :class="idx === 0 ? 'pt-0' : ''">
                                <div class="w-full sm:w-20 h-20 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-contain p-2">
                                </div>

                                <div class="flex-1 min-w-0 w-full sm:w-auto">
                                    <p class="text-sm font-semibold text-gray-900" x-text="item.name"></p>

                                    <div class="flex items-center justify-between sm:justify-start gap-4 mt-3">
                                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                            <button @click="updateQty(item.id, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                            <span class="qty-value w-8 text-center text-sm font-medium text-gray-800" x-text="item.qty"></span>
                                            <button @click="updateQty(item.id, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                        </div>

                                        <button @click="removeItem(item.id)" class="flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-red-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <div class="text-right shrink-0 w-full sm:w-auto">
                                    <p class="text-sm font-bold text-gray-900" x-text="'$' + (item.price * item.qty).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                                    <p class="text-xs text-gray-400 mt-0.5" x-text="'$' + item.price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' each'"></p>
                                    <p class="text-[9px] text-green-600 font-semibold mt-1" x-text="item.stock + ' left in stock'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Voucher Card --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2.59 12.58V6a2 2 0 0 1 2-2h6.58a2 2 0 0 1 1.41.59l8 8a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            <h2 class="text-sm font-semibold text-gray-900">Voucher / Coupon</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="voucherCode" placeholder="Enter code (SHOP20)" class="flex-1 min-w-0 px-3 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                            <button @click="applyVoucher()" class="shrink-0 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Apply</button>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Items (<span x-text="cartCount"></span>)</span>
                                <span class="font-medium text-gray-900" x-text="'$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-1.5 text-gray-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    Shipping
                                </span>
                                <span class="font-medium text-green-600">FREE</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Tax (8%)</span>
                                <span class="font-medium text-gray-900" x-text="'$' + tax.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 my-4"></div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="text-sm font-semibold text-gray-900">Grand Total</span>
                            <span class="text-lg font-bold text-gray-900" x-text="'$' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                        <a href="/dummy/checkout" class="w-full flex items-center justify-center gap-2 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold py-3 rounded-xl">
                            Proceed to Checkout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                        <p class="flex items-center gap-1.5 text-xs text-gray-400 mt-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Free shipping on this order
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function storeState() {
            return {
                view: 'catalog',
                selectedBrands: [],
                selectedCategory: '',
                searchOpen: false,
                searchQuery: '',
                cartItems: JSON.parse(localStorage.getItem('nexa_cart') || '[]'),
                voucherCode: '',

                steps: [
                    { label: 'Cart', key: 'cart' },
                    { label: 'Checkout', key: 'checkout' },
                    { label: 'Payment', key: 'payment' },
                    { label: 'Success', key: 'success' },
                ],

                get stepStates() {
                    const activeIdx = this.steps.findIndex(s => s.key === 'cart');
                    return this.steps.map((s, i) => ({
                        ...s,
                        state: i < activeIdx ? 'done' : i === activeIdx ? 'active' : 'upcoming'
                    }));
                },

                get cartCount() {
                    return this.cartItems.reduce((s, i) => s + i.qty, 0);
                },

                get subtotal() {
                    return this.cartItems.reduce((s, i) => s + i.price * i.qty, 0);
                },

                get tax() {
                    return Math.round(this.subtotal * 0.08 * 100) / 100;
                },

                get grandTotal() {
                    return Math.round((this.subtotal + this.tax) * 100) / 100;
                },

                save() {
                    localStorage.setItem('nexa_cart', JSON.stringify(this.cartItems));
                },

                updateQty(productId, delta) {
                    const item = this.cartItems.find(i => i.id === productId);
                    if (!item) return;
                    const newQty = Math.max(1, Math.min(item.qty + delta, item.stock));
                    const diff = newQty - item.qty;
                    if (diff > 0) {
                        fetch('/shop/decrement-stock', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ product_id: productId, quantity: diff })
                        }).catch(() => {});
                    } else if (diff < 0) {
                        fetch('/shop/restore-stock', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ product_id: productId, quantity: Math.abs(diff) })
                        }).catch(() => {});
                    }
                    item.qty = newQty;
                    this.save();
                },

                removeItem(productId) {
                    const item = this.cartItems.find(i => i.id === productId);
                    if (!item) return;
                    fetch('/shop/restore-stock', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ product_id: productId, quantity: item.qty })
                    }).catch(() => {});
                    this.cartItems = this.cartItems.filter(i => i.id !== productId);
                    this.save();
                },

                applyVoucher() {
                    if (!this.voucherCode.trim()) return;
                    console.log('Voucher applied:', this.voucherCode);
                },

                // Store stubs for nexa-header compatibility
                filterByCategory() {},
                filterAndScroll(category) { window.location.href = '/shop?category=' + category; },
                filterBrandAndScroll(brand) { window.location.href = '/shop?brand=' + brand; },
                filterByBrand() {},
                resetToHome() { window.location.href = '/shop'; },
                resetFilters() {},
            }
        }
    </script>
</body>
</html>
