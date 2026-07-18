<main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
    <div x-show="view === 'catalog'" class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
        @include('pages.customer.shop.Components.nexa-filters')
        <div class="lg:col-span-3">
            @include('pages.customer.shop.Components.nexa-product-list-header')
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                <template x-for="product in sortedProducts" :key="product.id">
                    @include('pages.customer.shop.Components.nexa-product-card')
                </template>
            </div>
        </div>
    </div>

    <template x-if="view === 'detail' && selectedProduct">
        @include('pages.customer.shop.Components.nexa-product-detail')
    </template>

    <div x-show="view === 'cart'" class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Shopping Cart</h1>
                <p class="text-xs text-gray-400 mt-1" x-text="cartItems.length + ' item(s) in your cart'"></p>
            </div>
            <button @click="view = 'catalog'" class="text-xs font-semibold text-sky-500 hover:text-sky-600 transition flex items-center gap-1 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
                Continue Shopping
            </button>
        </div>

        <template x-if="cartItems.length === 0">
            <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center shadow-sm">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <p class="text-sm font-semibold text-gray-400">Your cart is empty.</p>
                <button @click="view = 'catalog'" class="inline-block mt-3 text-sm font-semibold text-sky-500 hover:text-sky-600 cursor-pointer">Start Shopping</button>
            </div>
        </template>

        <template x-if="cartItems.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-3">
                    <template x-for="(item, idx) in cartItems" :key="item.id">
                        <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
                            <div class="w-full sm:w-20 h-20 shrink-0 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden border border-gray-100">
                                <img :src="item.image" :alt="item.name" class="h-full w-full object-contain p-2">
                            </div>
                            <div class="flex-1 min-w-0 w-full sm:w-auto">
                                <p class="text-sm font-bold text-slate-900 truncate" x-text="item.name"></p>
                                <p class="text-xs font-semibold text-sky-600 mt-0.5" x-text="'$' + item.price.toLocaleString()"></p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shrink-0">
                                    <button @click="updateCartQty(item.id, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-sm font-bold">-</button>
                                    <span class="w-8 text-center text-xs font-bold text-slate-800" x-text="item.qty"></span>
                                    <button @click="updateCartQty(item.id, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-sm font-bold">+</button>
                                </div>
                                <div class="text-right shrink-0 min-w-[80px]">
                                    <p class="text-sm font-black text-slate-900" x-text="'$' + (item.price * item.qty).toLocaleString()"></p>
                                    <button @click="removeFromCart(item.id)" class="text-[10px] font-semibold text-red-400 hover:text-red-500 mt-1 cursor-pointer">Remove</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm h-fit">
                    <h2 class="text-sm font-bold text-slate-900 mb-4">Order Summary</h2>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Items (<span x-text="cartCount"></span>)</span>
                            <span class="font-semibold text-slate-900" x-text="'$' + cartTotal.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Shipping</span>
                            <span class="font-semibold text-green-600">FREE</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tax (8%)</span>
                            <span class="font-semibold text-slate-900" x-text="'$' + tax.toLocaleString()"></span>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 my-4"></div>
                    <div class="flex justify-between mb-5">
                        <span class="text-sm font-bold text-slate-900">Total</span>
                        <span class="text-lg font-black text-slate-900" x-text="'$' + grandTotal.toLocaleString()"></span>
                    </div>
                    <button class="w-full text-center bg-sky-400 hover:bg-sky-500 text-blue-950 font-black text-xs py-3.5 rounded-xl shadow-sm transition tracking-wide uppercase cursor-pointer">Proceed to Checkout</button>
                </div>
            </div>
        </template>
    </div>
</main>
