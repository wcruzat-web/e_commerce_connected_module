<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $dbProducts = Product::with(['category', 'specifications', 'compatibilities', 'reviews.user'])
            ->where('is_active', true)
            ->get();

        $products = $dbProducts->map(fn ($p) => $this->mapProduct($p))->values();

        $category = $request->input('category');
        $brands = $request->input('brands', []);
        $chipsets = $request->input('chipsets', []);
        $sockets = $request->input('sockets', []);
        $vram = $request->input('vram');

        if (!empty($category)) {
            $products = $products->where('category', $category);
        }
        if (!empty($brands)) {
            $products = $products->whereIn('brand', $brands);
        }
        if (!empty($chipsets)) {
            $products = $products->whereIn('chipset', $chipsets);
        }
        if (!empty($sockets)) {
            $products = $products->whereIn('socket', $sockets);
        }
        if (!empty($vram)) {
            $products = $products->where('vram', $vram);
        }

        return view('pages.customer.shop.index', compact('products'));
    }

    public function show(string $id): View
    {
        $product = Product::with(['category', 'specifications', 'compatibilities', 'reviews.user'])
            ->findOrFail($id);

        $mapped = $this->mapProduct($product);

        return view('pages.customer.shop.show', ['product' => $mapped]);
    }

    public function review(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = ProductReview::create([
            'product_id' => $data['product_id'],
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
            'rating' => $data['rating'],
            'created_at' => now(),
        ]);

        $product = Product::find($data['product_id']);
        $allRatings = ProductReview::where('product_id', $data['product_id'])->pluck('rating');
        $avgRating = round($allRatings->average(), 2);
        $product->update([
            'rating' => $avgRating,
            'review_count' => $allRatings->count(),
        ]);

        $dist = [0, 0, 0, 0, 0];
        foreach ($allRatings as $r) {
            if ($r >= 1 && $r <= 5) $dist[5 - $r]++;
        }
        $total = $allRatings->count();
        $reviewDistribution = array_map(fn($c) => $total > 0 ? round(($c / $total) * 100) : 0, $dist);

        return response()->json([
            'success' => true,
            'productRating' => $avgRating,
            'reviewDistribution' => $reviewDistribution,
            'review' => [
                'id' => $review->review_id,
                'name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'initials' => strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'U', 0, 1)),
                'comment' => $review->comment,
                'rating' => $review->rating,
                'createdAt' => $review->created_at->diffForHumans(),
            ],
        ]);
    }

    public function decrementStock(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        if ($product->stock < $data['quantity']) {
            return response()->json(['success' => false, 'message' => 'Not enough stock'], 422);
        }
        $product->decrement('stock', $data['quantity']);

        return response()->json([
            'success' => true,
            'new_stock' => $product->fresh()->stock,
        ]);
    }

    public function restoreStock(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $product->increment('stock', $data['quantity']);

        return response()->json([
            'success' => true,
            'new_stock' => $product->fresh()->stock,
        ]);
    }

    private function mapProduct($p): array
    {
        $groupedSpecs = $p->specifications->groupBy('category_name');
        $groupedCompat = $p->compatibilities->groupBy('category_name');
        $reviews = $p->reviews;
        $reviewCount = $reviews->count();
        $rating = $reviewCount > 0 ? round($reviews->avg('rating'), 2) : 0;
        $dist = [0, 0, 0, 0, 0];
        foreach ($reviews as $r) {
            if ($r->rating >= 1 && $r->rating <= 5) $dist[5 - $r->rating]++;
        }
        $total = $reviewCount;
        $reviewDistribution = $total > 0
            ? array_map(fn($c) => round(($c / $total) * 100), $dist)
            : [0, 0, 0, 0, 0];

        $detailSpecs = $groupedSpecs->map(function ($specs, $category) {
            return [
                'section' => $category,
                'items' => $specs->map(function ($s) {
                    return ['label' => $s->label, 'value' => $s->value ?? 'N/A'];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        $compatGroups = $groupedCompat->map(function ($items, $category) {
            return [
                'category' => $category,
                'items' => $items->pluck('item_name')->toArray(),
            ];
        })->values()->toArray();

        return [
            'id' => (string) $p->id,
            'name' => $p->name,
            'brand' => $p->brand ?? 'Generic',
            'price' => (float) ($p->sale_price ?: $p->price),
            'sku' => $p->sku,
            'category' => $p->category->name ?? 'Uncategorized',
            'image' => $p->featured_image ?? 'https://placehold.co/200x200?text=No+Image',
            'badge' => $p->badge ?? '',
            'badgeClass' => $p->badge === 'Sale' ? 'bg-red-500' : ($p->badge === 'Best Seller' ? 'bg-amber-500' : 'bg-blue-900'),
            'stock' => (int) $p->stock,
            'inStock' => $p->stock > 0,
            'rating' => $rating,
            'reviewCount' => $reviewCount,
            'categoryMeta' => strtoupper($p->category->name ?? ''),
            'chipset' => $p->specifications->firstWhere('label', 'Chipset')->value ?? '',
            'socket' => $p->specifications->firstWhere('label', 'Socket')->value ?? '',
            'vram' => '',
            'specs' => $p->specifications->take(3)->pluck('label')->toArray(),
            'atAGlance' => $p->specifications->take(6)->map(function ($s) {
                return ['label' => $s->label, 'value' => $s->value ?? 'N/A'];
            })->toArray(),
            'detailSpecs' => $detailSpecs,
            'compatGroups' => $compatGroups,
            'compatiblePsu' => [],
            'compatibleCases' => [],
            'platformSupport' => [],
            'userReviews' => $reviews->map(function ($r) {
                return [
                    'id' => $r->review_id,
                    'name' => ($r->user->first_name ?? '') . ' ' . ($r->user->last_name ?? ''),
                    'initials' => strtoupper(substr($r->user->first_name ?? 'A', 0, 1) . substr($r->user->last_name ?? 'U', 0, 1)),
                    'comment' => $r->comment ?? '',
                    'rating' => $r->rating ?? 0,
                    'createdAt' => $r->created_at ? $r->created_at->diffForHumans() : '',
                ];
            })->values()->toArray(),
            'reviewDistribution' => $reviewDistribution,
        ];
    }
}
