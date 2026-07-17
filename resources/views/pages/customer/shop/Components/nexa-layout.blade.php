<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaStore PC - Elite PC Hardware</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    {{-- CHANGES HERE: CSRF token needed by fetch() AJAX calls --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-slate-900" x-data="storeState()">

    @include('components.header.header')
    @include('pages.customer.shop.Components.nexa-hero')
    @include('pages.customer.shop.Components.nexa-main-content')
    @include('pages.customer.shop.Components.nexa-footer')

    <script>
        function storeState() {
            return {
                view: 'catalog', 
                activeTab: 'specs',
                qty: 1,
                selectedBrands: [],
                selectedChipsets: [],
                selectedSockets: [],
                selectedVram: '',
                selectedCategory: '',
                selectedProduct: null,
                searchOpen: false,
                searchQuery: '',
                newReview: '',
                userReviews: [],
                cartMsg: '',
                holdTimer: null,
                // CHANGES HERE: prevents double-clicks while AJAX is in-flight
                cartLoading: false,
                productsList: @json($products ?? []),

                get filteredProducts() {
                    return this.productsList.filter(product => {
                        const matchesCategory = !this.selectedCategory || product.category === this.selectedCategory;
                        const matchesBrand = this.selectedBrands.length === 0 || this.selectedBrands.includes(product.brand);
                        const matchesChipset = this.selectedChipsets.length === 0 || this.selectedChipsets.includes(product.chipset);
                        const matchesSocket = this.selectedSockets.length === 0 || this.selectedSockets.includes(product.socket);
                        const matchesVram = !this.selectedVram || product.vram === this.selectedVram;
                        return matchesCategory && matchesBrand && matchesChipset && matchesSocket && matchesVram;
                    });
                },

                toggleVram(vramVal) {
                    this.selectedVram = this.selectedVram === vramVal ? '' : vramVal;
                },

                filterByCategory(category) {
                    this.view = 'catalog';
                    this.selectedCategory = category;
                    this.selectedBrands = [];
                    this.selectedChipsets = [];
                    this.selectedSockets = [];
                    this.selectedVram = '';
                },

                filterAndScroll(category) {
                    this.filterByCategory(category);
                    setTimeout(() => document.querySelector('main')?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 50);
                },

                filterBrandAndScroll(brand) {
                    this.filterByBrand(brand);
                    setTimeout(() => document.querySelector('main')?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 50);
                },

                filterByBrand(brand) {
                    this.view = 'catalog';
                    this.selectedCategory = '';
                    this.selectedBrands = [brand];
                    this.selectedChipsets = [];
                    this.selectedSockets = [];
                    this.selectedVram = '';
                },

                filterByChipset(chipset) {
                    this.view = 'catalog';
                    this.selectedCategory = '';
                    this.selectedChipsets = [chipset];
                    this.selectedBrands = [];
                    this.selectedSockets = [];
                    this.selectedVram = '';
                },

                resetFilters() {
                    this.selectedCategory = '';
                    this.selectedBrands = [];
                    this.selectedChipsets = [];
                    this.selectedSockets = [];
                    this.selectedVram = '';
                },

                // CHANGES HERE: replaced fake "Added" simulation with real AJAX POST to cart.add
                addToCart(product, count) {
                    if (!product.inStock || this.cartLoading) return;
                    this.cartLoading = true;
                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ product_id: product.id, quantity: count })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.cartMsg = 'Added ' + product.id;
                        setTimeout(() => { if (this.cartMsg === 'Added ' + product.id) this.cartMsg = ''; }, 1500);
                        // CHANGES HERE: update header cart badge
                        const badge = document.querySelector('.js-cart-badge');
                        if (badge && data.cartCount) {
                            badge.textContent = data.cartCount;
                            badge.classList.remove('hidden');
                        }
                    })
                    .catch(() => {})
                    .finally(() => { this.cartLoading = false; });
                },

                submitReview() {
                    if (!this.newReview.trim()) return;
                    const now = new Date();
                    const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                    this.userReviews.unshift({ name: 'You', initials: 'You', comment: this.newReview.trim(), createdAt: dateStr + ' at ' + timeStr });
                    this.newReview = '';
                },

                openProductDetail(product) {
                    this.selectedProduct = product;
                    this.view = 'detail';
                    this.activeTab = 'specs';
                    this.qty = 1;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                openProductById(id) {
                    const product = this.productsList.find(p => p.id === id);
                    if (product) this.openProductDetail(product);
                },

                resetToHome() {
                    this.view = 'catalog';
                    this.resetFilters();
                }
            }
        }
    </script>
</body>
</html>
