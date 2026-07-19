<?php

// [ESTEBAN] — REST API, logic unchanged from original
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
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

        $products = $query->orderBy('product_id', 'desc')->get();

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:product_table,sku',
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $product = Product::create([
            'product_name' => $validated['product_name'],
            'sku' => $validated['sku'],
            'brand' => $validated['brand'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'product_image' => $imagePath,
            'is_featured' => false,
        ]);

        $primaryWarehouse = DB::table('warehouses')->first();
        if ($primaryWarehouse) {
            DB::table('warehouse_stock')->insert([
                'warehouse_id' => $primaryWarehouse->warehouse_id,
                'product_id' => $product->product_id,
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
            'product_name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:product_table,sku,' . $id . ',product_id',
            'brand' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete('products/' . $product->product_image);
            }
            $product->product_image = $request->file('product_image')->store('products', 'public');
        }

        $product->product_name = $validated['product_name'];
        $product->sku = $validated['sku'];
        $product->brand = $validated['brand'];
        $product->category = $validated['category'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->save();

        DB::table('warehouse_stock')
            ->where('product_id', $id)
            ->update(['quantity' => $validated['stock'], 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'product' => $product,
            'message' => 'Product updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        if ($product->product_image) {
            Storage::disk('public')->delete('products/' . $product->product_image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    public function toggleFeatured(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
}
