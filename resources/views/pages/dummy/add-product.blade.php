<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Add Product</h1>
            <p class="text-slate-500 text-sm mt-1">Fill in the details below</p>
        </div>

        @if (session('success'))
            <div id="success-msg" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl mb-6 text-sm font-semibold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ session('success') }}
            </div>
            <script>setTimeout(function(){ var el = document.getElementById('success-msg'); if(el) el.style.display = 'none'; }, 4000);</script>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-3.5 rounded-2xl mb-6 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                Please fix the errors below.
            </div>
        @endif

        <form method="POST" action="/dummy/add-product" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-200/80 p-8 space-y-6 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Product Name</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" required placeholder="e.g. NVIDIA RTX 4090"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                    @error('product_name') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="e.g. RTX-4090-24G"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                    @error('sku') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="e.g. NVIDIA"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category</label>
                    <select name="category_id" required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Price</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">$</span>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50/50 pl-8 pr-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                    </div>
                    @error('price') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-4 focus:ring-cyan-100 transition-all">
                    <p class="text-xs text-slate-400 mt-1.5">Set to 0 if out of stock.</p>
                    @error('stock') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Badge</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 cursor-pointer hover:border-cyan-400 transition-all has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50/30 has-[:checked]:ring-2 has-[:checked]:ring-cyan-200">
                            <input type="radio" name="badge" value="new" {{ old('badge', 'new') === 'new' ? 'checked' : '' }} class="accent-cyan-500">
                            <span class="text-sm font-medium text-slate-700">New</span>
                        </label>
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 cursor-pointer hover:border-amber-400 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/30 has-[:checked]:ring-2 has-[:checked]:ring-amber-200">
                            <input type="radio" name="badge" value="Sale" {{ old('badge') === 'Sale' ? 'checked' : '' }} class="accent-amber-500">
                            <span class="text-sm font-medium text-slate-700">Sale</span>
                        </label>
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 cursor-pointer hover:border-amber-600 transition-all has-[:checked]:border-amber-700 has-[:checked]:bg-amber-50/30 has-[:checked]:ring-2 has-[:checked]:ring-amber-300">
                            <input type="radio" name="badge" value="Best Seller" {{ old('badge') === 'Best Seller' ? 'checked' : '' }} class="accent-amber-700">
                            <span class="text-sm font-medium text-slate-700">Best Seller</span>
                        </label>
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 cursor-pointer hover:border-gray-400 transition-all has-[:checked]:border-gray-500 has-[:checked]:bg-gray-100/30 has-[:checked]:ring-2 has-[:checked]:ring-gray-300">
                            <input type="radio" name="badge" value="" {{ old('badge') === '' ? 'checked' : '' }} class="accent-gray-500">
                            <span class="text-sm font-medium text-slate-700">None</span>
                        </label>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Product Image</label>
                    <div class="flex items-center gap-4">
                        <label class="flex-1 flex items-center gap-3 rounded-xl border border-dashed border-gray-300 bg-gray-50/50 px-4 py-3 cursor-pointer hover:border-cyan-400 hover:bg-cyan-50/30 transition-all">
                            <svg class="w-6 h-6 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            <span class="text-sm text-gray-500 font-medium" id="file-label">Choose image file</span>
                            <input type="file" name="product_image" accept="image/*" class="hidden" onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'Choose image file'">
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Optional. JPG, PNG, or WebP.</p>
                    @error('product_image') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                Add Product
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-6 flex items-center justify-center gap-4">
            <a href="/shop" class="text-cyan-600 hover:text-cyan-700 font-semibold underline underline-offset-2">&larr; Back to Shop</a>
            <a href="/dummy/products" class="text-cyan-600 hover:text-cyan-700 font-semibold underline underline-offset-2">Manage Products &rarr;</a>
        </p>
    </div>
</body>
</html>