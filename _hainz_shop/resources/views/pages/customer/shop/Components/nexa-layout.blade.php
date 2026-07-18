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
</head>
<body class="bg-gray-50 text-slate-900" x-data="storeState()">

    @include('pages.customer.shop.Components.nexa-header')
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
                filterOpen: false,
                sortBy: 'Featured',
                showDeals: false,
                newReview: '',
                reviewRating: 0,
                userReviews: [],
                cartItems: JSON.parse(localStorage.getItem('nexa_cart') || '[]'),
                cartMsg: '',
                holdTimer: null,
                mobileMenu: false,
                isLoggedIn: {{ $isLoggedIn ? 'true' : 'false' }},
                productsList: @json($products),

                get filteredProducts() {
                    return this.productsList.filter(product => {
                        const matchesCategory = !this.selectedCategory || product.category === this.selectedCategory;
                        const matchesBrand = this.selectedBrands.length === 0 || this.selectedBrands.includes(product.brand);
                        const matchesChipset = this.selectedChipsets.length === 0 || this.selectedChipsets.includes(product.chipset);
                        const matchesSocket = this.selectedSockets.length === 0 || this.selectedSockets.includes(product.socket);
                        const matchesVram = !this.selectedVram || product.vram === this.selectedVram;
                        const matchesDeals = !this.showDeals || product.badge === 'new' || product.badge === 'Sale' || product.badge === 'Best Seller';
                        const q = this.searchQuery.toLowerCase().trim();
                        const matchesSearch = !q || product.name.toLowerCase().includes(q) || (product.brand && product.brand.toLowerCase().includes(q)) || (product.sku && product.sku.toLowerCase().includes(q)) || product.category.toLowerCase().includes(q);
                        return matchesCategory && matchesBrand && matchesChipset && matchesSocket && matchesVram && matchesDeals && matchesSearch;
                    });
                },

                get sortedProducts() {
                    const sorted = [...this.filteredProducts];
                    if (this.sortBy === 'Price: Low to High') {
                        sorted.sort((a, b) => a.price - b.price);
                    } else if (this.sortBy === 'Price: High to Low') {
                        sorted.sort((a, b) => b.price - a.price);
                    }
                    return sorted;
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
                    this.showDeals = false;
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
                    this.showDeals = false;
                },

                filterByDeals() {
                    this.view = 'catalog';
                    this.showDeals = true;
                    this.selectedCategory = '';
                    this.selectedBrands = [];
                    this.selectedChipsets = [];
                    this.selectedSockets = [];
                    this.selectedVram = '';
                    this.sortBy = 'Featured';
                    setTimeout(() => document.querySelector('main')?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 50);
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
                    this.showDeals = false;
                },

                saveCart() {
                    localStorage.setItem('nexa_cart', JSON.stringify(this.cartItems));
                },

                addToCart(product, count) {
                    if (!this.isLoggedIn) { window.location.href = '/login'; return; }
                    if (!product.inStock) return;
                    fetch('/shop/decrement-stock', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ product_id: product.id, quantity: count })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Stock error');
                        return r.json();
                    })
                    .then(data => {
                        // Update local product stock
                        product.stock = data.new_stock;
                        product.inStock = data.new_stock > 0;
                        const listItem = this.productsList.find(p => p.id === product.id);
                        if (listItem) {
                            listItem.stock = data.new_stock;
                            listItem.inStock = data.new_stock > 0;
                        }
                        // Update or add to cart
                        const existing = this.cartItems.find(i => i.id === product.id);
                        if (existing) {
                            existing.qty = Math.min(existing.qty + count, data.new_stock + count);
                        } else {
                            this.cartItems.push({ id: product.id, name: product.name, price: product.price, image: product.image, stock: data.new_stock, qty: count });
                        }
                        this.saveCart();
                        this.cartMsg = 'Added ' + product.id;
                        setTimeout(() => { if (this.cartMsg === 'Added ' + product.id) this.cartMsg = ''; }, 1500);
                    })
                    .catch(() => {});
                },

                removeFromCart(productId) {
                    const item = this.cartItems.find(i => i.id === productId);
                    if (!item) return;
                    fetch('/shop/restore-stock', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ product_id: productId, quantity: item.qty })
                    }).then(r => r.json()).then(data => {
                        const listItem = this.productsList.find(p => p.id === productId);
                        if (listItem) { listItem.stock = data.new_stock; listItem.inStock = data.new_stock > 0; }
                    }).catch(() => {});
                    this.cartItems = this.cartItems.filter(i => i.id !== productId);
                    this.saveCart();
                },

                updateCartQty(productId, delta) {
                    const item = this.cartItems.find(i => i.id === productId);
                    if (!item) return;
                    const newQty = Math.max(1, Math.min(item.qty + delta, item.stock));
                    const diff = newQty - item.qty;
                    if (diff > 0) {
                        fetch('/shop/decrement-stock', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ product_id: productId, quantity: diff })
                        }).then(r => r.json()).then(data => {
                            const listItem = this.productsList.find(p => p.id === productId);
                            if (listItem) { listItem.stock = data.new_stock; listItem.inStock = data.new_stock > 0; }
                        }).catch(() => {});
                    } else if (diff < 0) {
                        fetch('/shop/restore-stock', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ product_id: productId, quantity: Math.abs(diff) })
                        }).then(r => r.json()).then(data => {
                            const listItem = this.productsList.find(p => p.id === productId);
                            if (listItem) { listItem.stock = data.new_stock; listItem.inStock = data.new_stock > 0; }
                        }).catch(() => {});
                    }
                    item.qty = newQty;
                    this.saveCart();
                },

                get cartTotal() {
                    return this.cartItems.reduce((sum, i) => sum + i.price * i.qty, 0);
                },

                get cartCount() {
                    return this.cartItems.reduce((sum, i) => sum + i.qty, 0);
                },

                get tax() {
                    return Math.round(this.cartTotal * 0.08 * 100) / 100;
                },

                get grandTotal() {
                    return Math.round((this.cartTotal + this.tax) * 100) / 100;
                },

                submitReview() {
                    if (!this.newReview.trim() || !this.selectedProduct || this.reviewRating === 0) return;
                    fetch('/shop/review', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ product_id: this.selectedProduct.id, comment: this.newReview.trim(), rating: this.reviewRating })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.userReviews.unshift(data.review);
                            this.selectedProduct.reviewCount = (this.selectedProduct.reviewCount || 0) + 1;
                            this.selectedProduct.rating = data.productRating;
                            this.selectedProduct.reviewDistribution = data.reviewDistribution;
                            this.newReview = '';
                            this.reviewRating = 0;
                        }
                    })
                    .catch(() => { window.location.href = '/login'; });
                },

                openProductDetail(product) {
                    this.selectedProduct = product;
                    this.userReviews = product.userReviews || [];
                    this.view = 'detail';
                    this.activeTab = product.userReviews && product.userReviews.length > 0 ? 'reviews' : 'specs';
                    this.qty = 1;
                    this.newReview = '';
                    this.reviewRating = 0;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                openProductById(id) {
                    const product = this.productsList.find(p => p.id === id);
                    if (product) this.openProductDetail(product);
                },

                resetToHome() {
                    this.view = 'catalog';
                    this.resetFilters();
                    this.filterOpen = false;
                    this.mobileMenu = false;
                }
            }
        }
    </script>
</body>
</html>
