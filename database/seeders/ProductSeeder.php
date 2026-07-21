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
        $this->seedGpuAmd();
        $this->seedCpuAmd();
        $this->seedStorage();
        $this->seedPowerSupply();
        $this->seedPcCase();
        $this->seedCpuCooler();
        $this->seedMemoryAdditional();
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

    private function seedGpuAmd(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'amd-radeon-rx-7900-xtx'],
            [
                'brand' => 'AMD',
                'name' => 'Radeon RX 7900 XTX',
                'description' => 'AMD\'s flagship RDNA 3 graphics card delivering exceptional 4K gaming performance with 24 GB of GDDR6 memory.',
                'price' => 999.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('Graphics Cards'),
                'category' => 'Graphics Cards',
                'featured_image' => '/images/products/rx-7900xtx.svg',
                'stock' => 5,
                'sku' => 'AMD-RX-7900XTX',
                'badge' => null,
                'rating' => 4.7,
                'review_count' => 1234,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'GPU Architecture', 'label' => 'Compute Units', 'value' => '96', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'GPU Architecture', 'label' => 'Stream Processors', 'value' => '6,144', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'GPU Architecture', 'label' => 'Game Clock', 'value' => '2.3 GHz', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'GPU Architecture', 'label' => 'Boost Clock', 'value' => '2.5 GHz', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Memory', 'label' => 'VRAM', 'value' => '24 GB GDDR6', 'is_highlight' => true, 'sort_order' => 5],
            ['category_name' => 'Memory', 'label' => 'Memory Bus', 'value' => '384-bit', 'is_highlight' => true, 'sort_order' => 6],
            ['category_name' => 'Memory', 'label' => 'Bandwidth', 'value' => '960 GB/s', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Connectivity', 'label' => 'DisplayPort', 'value' => '2x DP 2.1, 1x DP 1.4a', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Connectivity', 'label' => 'HDMI', 'value' => '1x HDMI 2.1', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Thermal & Power', 'label' => 'TDP', 'value' => '355W', 'is_highlight' => true, 'sort_order' => 10],
            ['category_name' => 'Thermal & Power', 'label' => 'Recommended PSU', 'value' => '800W', 'is_highlight' => false, 'sort_order' => 11],
            ['category_name' => 'Physical', 'label' => 'Length', 'value' => '287 mm', 'is_highlight' => false, 'sort_order' => 12],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedCpuAmd(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'amd-ryzen-7-7800x3d'],
            [
                'brand' => 'AMD',
                'name' => 'Ryzen 7 7800X3D',
                'description' => 'The ultimate gaming processor with AMD 3D V-Cache technology for unmatched gaming performance.',
                'price' => 449.99,
                'sale_price' => 429.99,
                'category_id' => $this->getCategoryId('Processors'),
                'category' => 'Processors',
                'featured_image' => '/images/products/r7-7800x3d.svg',
                'stock' => 12,
                'sku' => 'AMD-7800X3D',
                'badge' => 'Sale',
                'rating' => 4.9,
                'review_count' => 3120,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'CPU Specifications', 'label' => 'Cores', 'value' => '8', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'CPU Specifications', 'label' => 'Threads', 'value' => '16', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'CPU Specifications', 'label' => 'Base Clock', 'value' => '4.2 GHz', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'CPU Specifications', 'label' => 'Max Turbo', 'value' => '5.0 GHz', 'is_highlight' => true, 'sort_order' => 4],
            ['category_name' => 'Cache', 'label' => 'L3 Cache', 'value' => '96 MB (with 3D V-Cache)', 'is_highlight' => true, 'sort_order' => 5],
            ['category_name' => 'Cache', 'label' => 'L2 Cache', 'value' => '8 MB', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Memory', 'label' => 'Memory Type', 'value' => 'DDR5-5200', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Memory', 'label' => 'Max Memory', 'value' => '128 GB', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Thermal & Power', 'label' => 'TDP', 'value' => '120W', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Platform', 'label' => 'Socket', 'value' => 'AM5', 'is_highlight' => true, 'sort_order' => 10],
            ['category_name' => 'Platform', 'label' => 'Chipset', 'value' => 'X670E / B650', 'is_highlight' => false, 'sort_order' => 11],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedStorage(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'samsung-990-pro-nvme-2tb'],
            [
                'brand' => 'Samsung',
                'name' => '990 Pro NVMe SSD 2TB',
                'description' => 'Samsung\'s fastest consumer SSD with PCIe 4.0, delivering blazing sequential read speeds up to 7,450 MB/s.',
                'price' => 249.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('Storage'),
                'category' => 'Storage',
                'featured_image' => '/images/products/990-pro.svg',
                'stock' => 18,
                'sku' => 'SS-990PRO-2TB',
                'badge' => 'Best Seller',
                'rating' => 4.8,
                'review_count' => 4512,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Capacity', 'label' => 'Size', 'value' => '2 TB', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Performance', 'label' => 'Sequential Read', 'value' => '7,450 MB/s', 'is_highlight' => true, 'sort_order' => 2],
            ['category_name' => 'Performance', 'label' => 'Sequential Write', 'value' => '6,900 MB/s', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'Performance', 'label' => 'Random Read', 'value' => '1,400K IOPS', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Performance', 'label' => 'Random Write', 'value' => '1,550K IOPS', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Interface', 'label' => 'Form Factor', 'value' => 'M.2 2280', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Interface', 'label' => 'Interface', 'value' => 'PCIe 4.0 x4, NVMe 2.0', 'is_highlight' => true, 'sort_order' => 7],
            ['category_name' => 'Physical', 'label' => 'Controller', 'value' => 'Samsung Pascal', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Physical', 'label' => 'NAND', 'value' => 'Samsung V-NAND 3-bit MLC', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Durability', 'label' => 'TBW', 'value' => '1,200 TBW', 'is_highlight' => false, 'sort_order' => 10],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedPowerSupply(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'corsair-rm850x'],
            [
                'brand' => 'Corsair',
                'name' => 'RM850x (2024) 850W 80+ Gold',
                'description' => 'Fully modular 850W power supply with 80+ Gold efficiency, 135mm ML fan, and 10-year warranty.',
                'price' => 139.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('Power Supplies'),
                'category' => 'Power Supplies',
                'featured_image' => '/images/products/rm850x.svg',
                'stock' => 22,
                'sku' => 'CO-RM850X-2024',
                'badge' => null,
                'rating' => 4.7,
                'review_count' => 3891,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Power', 'label' => 'Wattage', 'value' => '850W', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Power', 'label' => 'Efficiency', 'value' => '80+ Gold', 'is_highlight' => true, 'sort_order' => 2],
            ['category_name' => 'Power', 'label' => 'AC Input', 'value' => '100-240V, 10A, 50-60Hz', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'Cooling', 'label' => 'Fan', 'value' => '135mm ML Magnetic Levitation', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Cooling', 'label' => 'Fan Mode', 'value' => 'Zero RPM (semi-passive)', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Cabling', 'label' => 'Type', 'value' => 'Fully Modular', 'is_highlight' => true, 'sort_order' => 6],
            ['category_name' => 'Cabling', 'label' => 'EPS', 'value' => '2x 4+4 pin', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Cabling', 'label' => 'PCIe', 'value' => '4x 6+2 pin', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Physical', 'label' => 'Form Factor', 'value' => 'ATX', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Physical', 'label' => 'Length', 'value' => '150 mm', 'is_highlight' => false, 'sort_order' => 10],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedPcCase(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'fractal-design-north-mesh'],
            [
                'brand' => 'Fractal Design',
                'name' => 'North Mesh ATX (Walnut)',
                'description' => 'A premium ATX case with a striking walnut wood front panel, mesh side panel, and excellent airflow.',
                'price' => 179.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('PC Cases'),
                'category' => 'PC Cases',
                'featured_image' => '/images/products/north-mesh.svg',
                'stock' => 8,
                'sku' => 'FD-NORTH-MESH',
                'badge' => null,
                'rating' => 4.9,
                'review_count' => 2547,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Form Factor', 'label' => 'Type', 'value' => 'ATX Mid Tower', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Form Factor', 'label' => 'Motherboard Support', 'value' => 'ATX, mATX, Mini ITX', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'Dimensions', 'label' => 'Height', 'value' => '447 mm', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'Dimensions', 'label' => 'Width', 'value' => '220 mm', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Dimensions', 'label' => 'Depth', 'value' => '445 mm', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Cooling', 'label' => 'Front Fans', 'value' => '3x 120mm / 2x 140mm (2x 140mm included)', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Cooling', 'label' => 'Top Fans', 'value' => '2x 120/140mm', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Cooling', 'label' => 'Rear Fans', 'value' => '1x 120mm', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Storage', 'label' => '2.5" Drive Bays', 'value' => '4', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Storage', 'label' => '3.5" Drive Bays', 'value' => '2', 'is_highlight' => false, 'sort_order' => 10],
            ['category_name' => 'Clearance', 'label' => 'GPU Max Length', 'value' => '355 mm', 'is_highlight' => true, 'sort_order' => 11],
            ['category_name' => 'Clearance', 'label' => 'CPU Cooler Max Height', 'value' => '170 mm', 'is_highlight' => true, 'sort_order' => 12],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedCpuCooler(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'noctua-nh-d15-chromax'],
            [
                'brand' => 'Noctua',
                'name' => 'NH-D15 chromax.Black',
                'description' => 'The award-winning dual-tower CPU cooler in all-black, delivering near-silent premium cooling performance.',
                'price' => 119.99,
                'sale_price' => null,
                'category_id' => $this->getCategoryId('CPU Coolers'),
                'category' => 'CPU Coolers',
                'featured_image' => '/images/products/nh-d15.svg',
                'stock' => 14,
                'sku' => 'NO-D15-CHROMAX',
                'badge' => null,
                'rating' => 4.9,
                'review_count' => 5872,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Cooler', 'label' => 'Type', 'value' => 'Dual Tower Air Cooler', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Cooler', 'label' => 'Fans', 'value' => '2x NF-A15 PWM 140mm', 'is_highlight' => false, 'sort_order' => 2],
            ['category_name' => 'Cooler', 'label' => 'Fan Speed', 'value' => '300-1500 RPM', 'is_highlight' => false, 'sort_order' => 3],
            ['category_name' => 'Cooler', 'label' => 'Noise Level', 'value' => '24.6 dBA', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Performance', 'label' => 'TDP Rating', 'value' => '~250W', 'is_highlight' => true, 'sort_order' => 5],
            ['category_name' => 'Physical', 'label' => 'Height', 'value' => '165 mm', 'is_highlight' => true, 'sort_order' => 6],
            ['category_name' => 'Physical', 'label' => 'Width', 'value' => '150 mm', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Physical', 'label' => 'Depth', 'value' => '161 mm (with fans)', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Compatibility', 'label' => 'Intel Sockets', 'value' => 'LGA 1851, 1700, 1200, 115x', 'is_highlight' => false, 'sort_order' => 9],
            ['category_name' => 'Compatibility', 'label' => 'AMD Sockets', 'value' => 'AM5, AM4', 'is_highlight' => false, 'sort_order' => 10],
        ];

        $this->syncSpecs($product, $specs);
    }

    private function seedMemoryAdditional(): void
    {
        $product = Product::updateOrCreate(
            ['slug' => 'corsair-vengeance-ddr5-32gb-5600'],
            [
                'brand' => 'Corsair',
                'name' => 'Vengeance DDR5-5600 32GB',
                'description' => 'High-performance DDR5 memory optimized for Intel and AMD platforms with low CL36 latency.',
                'price' => 99.99,
                'sale_price' => 89.99,
                'category_id' => $this->getCategoryId('Memory'),
                'category' => 'Memory',
                'featured_image' => '/images/products/vengeance-ddr5.svg',
                'stock' => 35,
                'sku' => 'CO-VEN-D5-32GB',
                'badge' => 'Sale',
                'rating' => 4.5,
                'review_count' => 6789,
                'is_active' => true,
            ]
        );

        $specs = [
            ['category_name' => 'Memory', 'label' => 'Capacity', 'value' => '32 GB (2 x 16 GB)', 'is_highlight' => true, 'sort_order' => 1],
            ['category_name' => 'Memory', 'label' => 'Type', 'value' => 'DDR5', 'is_highlight' => true, 'sort_order' => 2],
            ['category_name' => 'Memory', 'label' => 'Speed', 'value' => '5600 MT/s', 'is_highlight' => true, 'sort_order' => 3],
            ['category_name' => 'Memory', 'label' => 'Timings', 'value' => 'CL36-36-36-76', 'is_highlight' => false, 'sort_order' => 4],
            ['category_name' => 'Memory', 'label' => 'Voltage', 'value' => '1.25V', 'is_highlight' => false, 'sort_order' => 5],
            ['category_name' => 'Physical', 'label' => 'Height', 'value' => '34 mm (low profile)', 'is_highlight' => false, 'sort_order' => 6],
            ['category_name' => 'Physical', 'label' => 'Color', 'value' => 'Black', 'is_highlight' => false, 'sort_order' => 7],
            ['category_name' => 'Compatibility', 'label' => 'Intel XMP', 'value' => 'XMP 3.0', 'is_highlight' => false, 'sort_order' => 8],
            ['category_name' => 'Compatibility', 'label' => 'AMD EXPO', 'value' => 'EXPO Ready', 'is_highlight' => false, 'sort_order' => 9],
        ];

        $this->syncSpecs($product, $specs);
    }
}

