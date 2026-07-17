<?php

namespace App\Http\Controllers;

use App\Services\ProductSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    private function catalog(): array
    {
        $items = ProductSource::items();
        $products = [];

        foreach ($items as $item) {
            $category = $item['category'];
            $brands = [
                'Processor' => 'Intel',
                'GPU' => 'NVIDIA',
                'Memory' => 'Corsair',
                'Storage' => 'Samsung',
                'Motherboard' => 'ASUS',
                'Power' => 'Cooler Master',
            ];

            $products[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'category' => $category,
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => $item['image'],
                'keywords' => $item['keywords'],
                'brand' => $brands[$category] ?? 'Generic',
                'rating' => 4.5,
                'reviewCount' => rand(10, 200),
                'inStock' => true,
                'chipset' => '',
                'socket' => '',
                'vram' => '',
                'badge' => '',
                'features' => [],
                'specs' => [],
            ];
        }

        return $products;
    }

    public function home(): View
    {
        $products = $this->catalog();

        return view('pages.customer.shop.shop', compact('products'));
    }
}
