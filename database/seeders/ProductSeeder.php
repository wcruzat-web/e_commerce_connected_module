<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedGpu();
        $this->seedCpu();
        $this->seedMotherboard();
        $this->seedMemory();
    }

    private function getCategoryId(string $categoryName): int
    {
        $category = Category::where('name', $categoryName)->first();
        if (!$category) {
            throw new \Exception("Category '{$categoryName}' not found. Run CategorySeeder first.");
        }
        return $category->id;
    }

    private function syncSpecs(Product $product, array $specs): void
    {
        $product->specifications()->delete();
        foreach ($specs as $spec) {
            $product->specifications()->create($spec);
        }
    }

    private function seedGpu(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'nvidia-geforce-rtx-4090-fe'],
            [
                'brand' => 'NVIDIA',
                'name' => 'GeForce RTX 4090 Founders Edition',
                'description' => "Experience the ultimate in gaming and creative performance with the NVIDIA GeForce RTX 4090. Powered by the Ada Lovelace architecture, it delivers a massive leap in performance and efficiency.",
                'price' => 1599.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('Graphics Cards'),
                'category' => 'Graphics Cards',
                'featured_image' => '/images/products/rtx-4090-fe.svg',
                'stock' => 2,
                'sku' => 'NV-4090-FE',
                'badge' => 'Only 4 Left',
                'rating' => 4.8,
                'review_count' => 2847,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'GPU Architecture', 'label' => 'CUDA Cores', 'value' => '16,384', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'GPU Architecture', 'label' => 'RT Cores', 'value' => '128 (3rd Gen)', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'GPU Architecture', 'label' => 'Tensor Cores', 'value' => '512 (4th Gen)', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'GPU Architecture', 'label' => 'Base Clock', 'value' => '2.23 GHz', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Memory', 'label' => 'VRAM', 'value' => '24 GB GDDR6X', 'is_highlight' => true, 'sort_order' => 5],
            ['category_name' => 'Memory', 'label' => 'Memory Type', 'value' => 'GDDR6X', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Memory', 'label' => 'Memory Bus', 'value' => '384-bit', 'is_highlight' => true, 'sort_order' => 7],
            ['category_name' => 'Memory', 'label' => 'Bandwidth', 'value' => '1,008 GB/s', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Performance', 'label' => 'Boost Clock', 'value' => '2.52 GHz', 'is_highlight' => true, 'sort_order' => 9],
            ['category_name' => 'Performance', 'label' => 'Shader Units', 'value' => '16,384', 'is_highlight' => false, 'sort_order' => 10],
            ['category_name' => 'Performance', 'label' => 'AI Performance', 'value' => '1,321 TOPS', 'is_highlight' => false, 'sort_order' => 11],
            ['category_name' => 'Thermal & Power', 'label' => 'TDP', 'value' => '450W', 'is_highlight' => true, 'sort_order' => 12],
            ['category_name' => 'Thermal & Power', 'label' => 'Recommended PSU', 'value' => '850W', 'is_highlight' => false, 'sort_order' => 13],
            ['category_name' => 'Thermal & Power', 'label' => 'Cooling Type', 'value' => 'Dual Axial Flow Through', 'is_highlight' => false, 'sort_order' => 14],
            ['category_name' => 'Connectivity', 'label' => 'DisplayPort', 'value' => '3x DisplayPort 1.4a', 'is_highlight' => true, 'sort_order' => 15],
            ['category_name' => 'Connectivity', 'label' => 'HDMI', 'value' => '1x HDMI 2.1', 'is_highlight' => false, 'sort_order' => 16],
            ['category_name' => 'Connectivity', 'label' => 'PCIe', 'value' => 'PCIe 4.0 x16', 'is_highlight' => false, 'sort_order' => 17],
            ['category_name' => 'Physical', 'label' => 'Length', 'value' => '304 mm', 'is_highlight' => false, 'sort_order' => 18],
            ['category_name' => 'Physical', 'label' => 'Width', 'value' => '137 mm', 'is_highlight' => false, 'sort_order' => 19],
            ['category_name' => 'Physical', 'label' => 'Height', 'value' => '2.5 slots', 'is_highlight' => false, 'sort_order' => 20],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedCpu(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'intel-core-i9-14900k'],
            [
                'brand' => 'Intel',
                'name' => 'Core i9-14900K',
                'description' => "The Intel Core i9-14900K delivers exceptional performance for gaming and content creation with 24 cores and 32 threads.",
                'price' => 589.99,
                'sale_price' => 549.99,
                'category_id' => $this->getCategoryId('Processors'),
                'category' => 'Processors',
                'featured_image' => '/images/products/i9-14900k.svg',
                'stock' => 7,
                'sku' => 'IN-14900K',
                'badge' => 'Sale',
                'rating' => 4.7,
                'review_count' => 1532,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'CPU Specifications', 'label' => 'Cores', 'value' => '24 (8 P-cores + 16 E-cores)', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'CPU Specifications', 'label' => 'Threads', 'value' => '32', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'CPU Specifications', 'label' => 'Base Clock', 'value' => '3.2 GHz', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'CPU Specifications', 'label' => 'Max Turbo', 'value' => '6.0 GHz', 'is_highlight' => true, 'sort_order' => 4],
            ['category_name' => 'Cache', 'label' => 'L3 Cache', 'value' => '36 MB', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Cache', 'label' => 'L2 Cache', 'value' => '32 MB', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Memory', 'label' => 'Memory Type', 'value' => 'DDR5-5600 / DDR4-3200', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Memory', 'label' => 'Max Memory', 'value' => '192 GB', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Thermal & Power', 'label' => 'TDP', 'value' => '125W (base) / 253W (turbo)', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Platform', 'label' => 'Socket', 'value' => 'LGA 1700', 'is_highlight' => true, 'sort_order' => 10],
            ['category_name' => 'Platform', 'label' => 'Chipset', 'value' => 'Z790 / Z690 / B760', 'is_highlight' => false, 'sort_order' => 11],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedMotherboard(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'asus-rog-maximus-z790-hero'],
            [
                'brand' => 'ASUS',
                'name' => 'ROG Maximus Z790 Hero',
                'description' => "The ROG Maximus Z790 Hero is built for Intel 13th & 14th Gen processors, featuring robust power delivery and premium audio.",
                'price' => 629.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('Motherboards'),
                'category' => 'Motherboards',
                'featured_image' => '/images/products/z790-hero.svg',
                'stock' => 4,
                'sku' => 'AS-Z790-HERO',
                'badge' => 'Best Seller',
                'rating' => 4.9,
                'review_count' => 968,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Form Factor', 'label' => 'Size', 'value' => 'ATX', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Form Factor', 'label' => 'Width', 'value' => '244 mm', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'CPU', 'label' => 'Socket', 'value' => 'LGA 1700', 'is_highlight' => true, 'sort_order' => 3],
            ['category_name' => 'CPU', 'label' => 'Chipset', 'value' => 'Intel Z790', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Memory', 'label' => 'Slots', 'value' => '4x DDR5', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Memory', 'label' => 'Max Memory', 'value' => '192 GB DDR5', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Expansion', 'label' => 'PCIe x16', 'value' => '2x PCIe 5.0 x16', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Storage', 'label' => 'M.2 Slots', 'value' => '5x (1x PCIe 5.0)', 'is_highlight' => true, 'sort_order' => 8],
            ['category_name' => 'Storage', 'label' => 'SATA', 'value' => '6x SATA III', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Connectivity', 'label' => 'USB', 'value' => '2x USB-C 3.2 Gen 2, 8x USB-A', 'is_highlight' => false, 'sort_order' => 10],
            ['category_name' => 'Connectivity', 'label' => 'LAN', 'value' => '2.5 Gb Ethernet', 'is_highlight' => false, 'sort_order' => 11],
            ['category_name' => 'Connectivity', 'label' => 'Wi-Fi', 'value' => 'Wi-Fi 6E', 'is_highlight' => true, 'sort_order' => 12],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedMemory(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'gskill-trident-z5-rgb-ddr5-6000-32gb'],
            [
                'brand' => 'G.Skill',
                'name' => 'Trident Z5 RGB DDR5-6000 32GB',
                'description' => "High-performance DDR5 memory kit with RGB lighting, optimized for Intel and AMD platforms.",
                'price' => 129.99,
                'sale_price' => 109.99,
                'category_id' => $this->getCategoryId('Memory'),
                'category' => 'Memory',
                'featured_image' => '/images/products/trident-z5.svg',
                'stock' => 25,
                'sku' => 'GS-TZ5-32GB',
                'badge' => null,
                'rating' => 4.6,
                'review_count' => 2145,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Memory', 'label' => 'Capacity', 'value' => '32 GB (2 x 16 GB)', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Memory', 'label' => 'Type', 'value' => 'DDR5', 'is_highlight' => true, 'sort_order' => 2],
            ['category_name' => 'Memory', 'label' => 'Speed', 'value' => '6000 MT/s', 'is_highlight' => true, 'sort_order' => 3],
            ['category_name' => 'Memory', 'label' => 'Timings', 'value' => 'CL30-38-38-96', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Memory', 'label' => 'Voltage', 'value' => '1.35V', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Physical', 'label' => 'Height', 'value' => '44 mm', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Physical', 'label' => 'Color', 'value' => 'Black / Silver', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Compatibility', 'label' => 'Intel XMP', 'value' => 'XMP 3.0', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Compatibility', 'label' => 'AMD EXPO', 'value' => 'EXPO Ready', 'is_highlight' => false, 'sort_order' => 9],
        ];

        $this->syncSpecs($product, $specs);
    }
}

