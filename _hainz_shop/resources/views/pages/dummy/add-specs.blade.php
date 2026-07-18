<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Specifications & Compatibility</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="/dummy/add-product" class="text-blue-600 hover:underline">&larr; Back to Add Product</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold mb-2">Add Specifications & Compatibility</h1>
            <p class="text-gray-600">Product: <strong>{{ $product->name }}</strong> (SKU: {{ $product->sku }})</p>
        </div>

        <form method="POST" action="/dummy/add-specs/{{ $product->id }}" x-data="specsForm()">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Specifications</h2>
                <p class="text-sm text-gray-500 mb-4">Add technical specifications for this product.</p>

                <template x-for="(spec, index) in specs" :key="index">
                    <div class="flex gap-3 mb-3 items-start">
                        <input type="hidden" :name="'specs[' + index + '][category_name]'" :value="spec.category_name">
                        <input type="hidden" :name="'specs[' + index + '][attribute_name]'" :value="spec.attribute_name">
                        <input type="hidden" :name="'specs[' + index + '][attribute_value]'" :value="spec.attribute_value">
                        <select x-model="spec.category_name" class="border rounded px-3 py-2 w-48">
                            <option value="">Select category</option>
                            @foreach($specCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        <input type="text" x-model="spec.attribute_name" placeholder="Attribute name (e.g. GPU Architecture)" class="border rounded px-3 py-2 flex-1">
                        <input type="text" x-model="spec.attribute_value" placeholder="Value (e.g. 24GB)" class="border rounded px-3 py-2 flex-1">
                        <button type="button" @click="removeSpec(index)" class="text-red-500 hover:text-red-700 px-2 py-2">&times;</button>
                    </div>
                </template>

                <button type="button" @click="addSpec()" class="text-blue-600 hover:text-blue-800 text-sm mt-2">+ Add specification row</button>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Compatibility</h2>
                <p class="text-sm text-gray-500 mb-4">Add compatible items for this product (PSU, cases, platforms, etc.).</p>

                <template x-for="(compat, index) in compatibilities" :key="index">
                    <div class="flex gap-3 mb-3 items-start">
                        <input type="hidden" :name="'compatibilities[' + index + '][category_name]'" :value="compat.category_name">
                        <input type="hidden" :name="'compatibilities[' + index + '][item_name]'" :value="compat.item_name">
                        <select x-model="compat.category_name" class="border rounded px-3 py-2 w-48">
                            <option value="">Select category</option>
                            @foreach($compatCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        <input type="text" x-model="compat.item_name" placeholder="Item name (e.g. Corsair HX1000i)" class="border rounded px-3 py-2 flex-1">
                        <button type="button" @click="removeCompat(index)" class="text-red-500 hover:text-red-700 px-2 py-2">&times;</button>
                    </div>
                </template>

                <button type="button" @click="addCompat()" class="text-blue-600 hover:text-blue-800 text-sm mt-2">+ Add compatibility row</button>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Save Specifications & Compatibility</button>
                <a href="/dummy/add-product" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400">Done &mdash; Add Another Product</a>
            </div>
        </form>
    </div>

    <script>
        function specsForm() {
            return {
                specs: [],
                compatibilities: [],
                addSpec() {
                    this.specs.push({ category_name: '', attribute_name: '', attribute_value: '' });
                },
                removeSpec(index) {
                    this.specs.splice(index, 1);
                },
                addCompat() {
                    this.compatibilities.push({ category_name: '', item_name: '' });
                },
                removeCompat(index) {
                    this.compatibilities.splice(index, 1);
                }
            }
        }
    </script>
</body>
</html>
