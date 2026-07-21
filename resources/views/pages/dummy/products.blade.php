<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Manage Products</h1>
            <div class="flex gap-3">
                <a href="/dummy/add-product" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Product</a>
                <a href="/shop" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400">Shop</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Product</th>
                        <th class="px-4 py-3 font-semibold">SKU</th>
                        <th class="px-4 py-3 font-semibold">Price</th>
                        <th class="px-4 py-3 font-semibold">Stock</th>
                        <th class="px-4 py-3 font-semibold">Specs</th>
                        <th class="px-4 py-3 font-semibold">Compat</th>
                        <th class="px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $p->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->sku }}</td>
                            <td class="px-4 py-3">₱{{ number_format($p->price, 2) }}</td>
                            <td class="px-4 py-3">{{ $p->stock }}</td>
                            <td class="px-4 py-3">{{ $p->specifications_count }}</td>
                            <td class="px-4 py-3">{{ $p->compatibilities_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 items-center">
                                    <a href="/dummy/edit-product/{{ $p->id }}" class="text-cyan-600 hover:underline font-semibold text-xs">Edit</a>
                                    <a href="/dummy/edit-specs/{{ $p->id }}" class="text-blue-600 hover:underline font-semibold text-xs">Specs & Compat</a>
                                    <form method="POST" action="/dummy/products/{{ $p->id }}" onsubmit="return confirm('Delete {{ $p->name }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 hover:underline font-semibold text-xs">Remove</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
