<div class="flex items-center justify-between flex-wrap gap-4 mb-6 animate-fade-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-500 mt-1">View all your orders in one place.</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-lg font-medium transition hover:-translate-y-0.5 hover-lift">
            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-bag.svg"
                 class="w-4 h-4" alt="">
            Continue shopping
        </a>
    </div>
