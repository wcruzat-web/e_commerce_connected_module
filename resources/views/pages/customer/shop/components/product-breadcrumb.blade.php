<nav class="text-xs font-semibold text-gray-400 flex items-center space-x-2">
    <a href="{{ route('products.index') }}" class="hover:text-blue-600">Shop</a>
    <span>/</span>
    <span class="text-slate-900">{{ $product['name'] }}</span>
</nav>
