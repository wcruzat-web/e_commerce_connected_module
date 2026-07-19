<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['product_name' => 'NVIDIA RTX 4090 Founder Edition', 'sku' => 'NV-4090-FE', 'brand' => 'NVIDIA', 'category' => 'GPU', 'price' => 1599.00, 'stock' => 2, 'is_featured' => true],
            ['product_name' => 'Intel Core i9-14900K Processor', 'sku' => 'IN-14900K', 'brand' => 'Intel', 'category' => 'CPU', 'price' => 589.00, 'stock' => 7, 'is_featured' => true],
            ['product_name' => 'ASUS ROG Maximus Z790 Hero', 'sku' => 'AS-Z7090-MX', 'brand' => 'ASUS', 'category' => 'Motherboard', 'price' => 699.00, 'stock' => 4, 'is_featured' => false],
            ['product_name' => 'Corsair Vengeance DDR5 64GB', 'sku' => 'CO-DDR5-64', 'brand' => 'Corsair', 'category' => 'Memory', 'price' => 189.00, 'stock' => 11, 'is_featured' => false],
            ['product_name' => 'NZXT Kraken 360 AIO Cooler', 'sku' => 'NZ-KR360', 'brand' => 'NZXT', 'category' => 'Cooling', 'price' => 159.00, 'stock' => 18, 'is_featured' => false],
            ['product_name' => 'AMD Ryzen 9 7950X Processor', 'sku' => 'AM-7950X', 'brand' => 'AMD', 'category' => 'CPU', 'price' => 549.00, 'stock' => 9, 'is_featured' => true],
            ['product_name' => 'Samsung 990 Pro NVMe 2TB', 'sku' => 'SM-990P-2T', 'brand' => 'Samsung', 'category' => 'Memory', 'price' => 179.00, 'stock' => 0, 'is_featured' => false],
            ['product_name' => 'MSI Suprim X RTX 4080', 'sku' => 'MS-4080-SX', 'brand' => 'MSI', 'category' => 'GPU', 'price' => 1199.00, 'stock' => 3, 'is_featured' => false],
        ];

        foreach ($products as $p) {
            $p['created_at'] = now();
            $p['updated_at'] = now();
        }

        DB::table('product_table')->insert($products);

        $warehouses = [
            ['warehouse_name' => 'Warehouse A - Singapore', 'location' => 'Singapore', 'sync_status' => 'Synced', 'last_sync_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['warehouse_name' => 'Warehouse B - Manila', 'location' => 'Manila', 'sync_status' => 'Synced', 'last_sync_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['warehouse_name' => 'Warehouse C - Jakarta', 'location' => 'Jakarta', 'sync_status' => 'Pending', 'last_sync_at' => now()->subMinutes(20), 'created_at' => now(), 'updated_at' => now()],
            ['warehouse_name' => 'ERP Central Hub', 'location' => 'Central', 'sync_status' => 'Synced', 'last_sync_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('warehouses')->insert($warehouses);

        $primaryWarehouseId = DB::table('warehouses')->first()->warehouse_id;
        $allProducts = DB::table('product_table')->get();

        foreach ($allProducts as $product) {
            DB::table('warehouse_stock')->insert([
                'warehouse_id' => $primaryWarehouseId,
                'product_id' => $product->product_id,
                'quantity' => $product->stock,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('promo_banners')->insert([
            ['title' => 'Summer GPU Sale', 'subtitle' => '15% OFF', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $revenueData = [
            ['month_label' => 'July', 'overview_year' => 2025, 'revenue_amount' => 15200.00, 'created_at' => now()],
            ['month_label' => 'August', 'overview_year' => 2025, 'revenue_amount' => 17400.00, 'created_at' => now()],
            ['month_label' => 'September', 'overview_year' => 2025, 'revenue_amount' => 19800.00, 'created_at' => now()],
            ['month_label' => 'October', 'overview_year' => 2025, 'revenue_amount' => 18100.00, 'created_at' => now()],
            ['month_label' => 'November', 'overview_year' => 2025, 'revenue_amount' => 17600.00, 'created_at' => now()],
            ['month_label' => 'December', 'overview_year' => 2025, 'revenue_amount' => 19000.00, 'created_at' => now()],
        ];

        DB::table('revenue_overview')->insert($revenueData);
    }
}
