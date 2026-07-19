<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductCompatibility;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// ESTEBAN — adapted from original: column mapping uses 'name' (was 'product_name'), 'featured_image' (was 'product_image'), 'id' (was 'product_id')
// ESTEBAN — added: 'storage/' prefix on image path (V2.3), badge/sale_price fields (V2.7), specs/compat (V2.6), category_id sync (V2.7), featured toggle (V2.5)
class ProductController extends \App\Http\Controllers\Controller
{
    public function index(Request $request): JsonResponse
    {
        // ESTEBAN — added: eager-load specifications and compatibilities relationships
        $query = Product::with(['specifications', 'compatibilities']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($brand = $request->input('brand')) {
            $query->where('brand', $brand);
        }

        if ($stock = $request->input('stock')) {
            if ($stock === 'In Stock') {
                $query->where('stock', '>', 5);
            } elseif ($stock === 'Low Stock') {
                $query->whereBetween('stock', [1, 5]);
            } elseif ($stock === 'Out of Stock') {
                $query->where('stock', 0);
            }
        }

        $products = $query->orderBy('id', 'desc')->get();

        return response()->json($products);
    }

    // ESTEBAN — added: wipe-and-replace pattern for Specifications (V2.6)
    private function saveSpecs(Product $product, ?array $specs): void
    {
        $product->specifications()->delete();
        if ($specs) {
            foreach ($specs as $spec) {
                $product->specifications()->create([
                    'category_name' => $spec['category_name'],
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                ]);
            }
        }
    }

    // ESTEBAN — added: syncs free-text category to category_id FK via firstOrCreate on categories table (V2.7)
    private function syncCategoryId(Product $product): void
    {
        if ($product->category) {
            $cat = Category::firstOrCreate(['name' => $product->category], ['slug' => str()->slug($product->category)]);
            $product->category_id = $cat->id;
            $product->save();
        }
    }

    // ESTEBAN — added: wipe-and-replace pattern for Compatibility (V2.6)
    private function saveCompat(Product $product, ?array $compat): void
    {
        $product->compatibilities()->delete();
        if ($compat) {
            foreach ($compat as $item) {
                ProductCompatibility::create([
                    'product_id' => $product->id,
                    'category_name' => $item['category_name'],
                    'item_name' => $item['item_name'],
                ]);
            }
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:products,sku',
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            // ESTEBAN — added: 'storage/' prefix so path matches customer-facing views convention (V2.3)
            $imagePath = 'storage/' . $request->file('featured_image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'brand' => $validated['brand'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            // ESTEBAN — added: badge (New/Sale) and original price fields (V2.7)
            'badge' => $request->input('badge', ''),
            'sale_price' => $request->input('badge') === 'Sale' ? $request->input('sale_price') : null,
            'featured_image' => $imagePath,
            'is_featured' => false,
        ]);

        // ESTEBAN — added: sync free-text category to category_id FK (V2.7)
        $this->syncCategoryId($product);

        // ESTEBAN — added: Specifications and Compatibility save (V2.6)
        $specs = json_decode($request->input('specs', '[]'), true) ?? [];
        $compat = json_decode($request->input('compat', '[]'), true) ?? [];
        $this->saveSpecs($product, $specs);
        $this->saveCompat($product, $compat);

        $primaryWarehouse = DB::table('warehouses')->first();
        if ($primaryWarehouse) {
            DB::table('warehouse_stock')->insert([
                'warehouse_id' => $primaryWarehouse->warehouse_id,
                'product_id' => $product->id,
                'quantity' => $validated['stock'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
            'message' => 'Product added successfully',
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                // ESTEBAN — added: strips 'storage/' before deleting old image from storage (V2.3)
                $oldPath = str_replace('storage/', '', $product->featured_image);
                Storage::disk('public')->delete($oldPath);
            }
            $product->featured_image = 'storage/' . $request->file('featured_image')->store('products', 'public');
        }

        $product->name = $validated['name'];
        $product->sku = $validated['sku'];
        $product->brand = $validated['brand'];
        $product->category = $validated['category'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->badge = $request->input('badge', '');
        $product->sale_price = $request->input('badge') === 'Sale' ? $request->input('sale_price') : null;
        $product->save();

        $this->syncCategoryId($product);

        $specs = json_decode($request->input('specs', '[]'), true) ?? [];
        $compat = json_decode($request->input('compat', '[]'), true) ?? [];
        $this->saveSpecs($product, $specs);
        $this->saveCompat($product, $compat);

        DB::table('warehouse_stock')
            ->where('product_id', $id)
            ->update([
                'quantity' => $validated['stock'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'product' => $product,
            'message' => 'Product updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        if ($product->featured_image) {
            // ESTEBAN — added: strips 'storage/' before deleting old image from storage (V2.3)
            $oldPath = str_replace('storage/', '', $product->featured_image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    // ESTEBAN — added: single-featured enforcement — unfeatures all others when setting a new featured product (V2.5)
    public function toggleFeatured(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $newValue = !$product->is_featured;

        if ($newValue) {
            Product::where('is_featured', true)->update(['is_featured' => false]);
        }

        $product->is_featured = $newValue;
        $product->save();

        $product = Product::find($id);

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
}
