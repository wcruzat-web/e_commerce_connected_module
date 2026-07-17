<main class="max-w-7xl mx-auto px-6 py-10">
    <div x-show="view === 'catalog'" class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('pages.customer.shop.Components.nexa-filters')
        <div class="lg:col-span-3">
            @include('pages.customer.shop.Components.nexa-product-list-header')
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    @include('pages.customer.shop.Components.nexa-product-card')
                </template>
            </div>
        </div>
    </div>

    <template x-if="view === 'detail' && selectedProduct">
        @include('pages.customer.shop.Components.nexa-product-detail')
    </template>
</main>
