{{-- HAINZ — product-breadcrumb: breadcrumb navigation on product detail page (ERPV1.1) --}}
<nav class="text-xs font-semibold text-gray-400 dark:text-gray-500 flex items-center space-x-2">
    <a href="{{ route('products.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Shop</a>
    <span>/</span>
    <span class="text-slate-900 dark:text-white">{{ $product['name'] }}</span>
</nav>
