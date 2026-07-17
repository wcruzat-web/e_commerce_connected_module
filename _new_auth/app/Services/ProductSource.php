<?php

namespace App\Services;

/**
 * Static dummy product catalog for the storefront.
 * Belongs to the Inventory + Sales modules (co-members); kept here as a
 * simple in-code source so the cart/wishlist flow works without a DB table.
 * Replace with a real products table/query later.
 */
class ProductSource
{
    /** @return array<int, array{id:int,name:string,category:string,description:string,price:float,image:string,keywords:string}> */
    public static function items(): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'Intel Core i5-12400F',
                'category' => 'Processor',
                'description' => '6 Cores / 12 Threads / 2.5 GHz — great value gaming CPU.',
                'price' => 8990.00,
                'image' => 'https://loremflickr.com/600/600/processor,cpu?lock=11',
                'keywords' => 'processor',
            ],
            2 => [
                'id' => 2,
                'name' => 'GeForce RTX 4060 Dual 8GB',
                'category' => 'GPU',
                'description' => 'GeForce RTX 4060 GDDR6 — smooth 1080p gaming.',
                'price' => 18500.00,
                'image' => 'https://loremflickr.com/600/600/graphics,card?lock=22',
                'keywords' => 'graphics card',
            ],
            3 => [
                'id' => 3,
                'name' => 'Corsair Vengeance 16GB DDR5',
                'category' => 'Memory',
                'description' => '16GB (2x8GB) DDR5 6000MHz RAM kit.',
                'price' => 5200.00,
                'image' => 'https://loremflickr.com/600/600/ram,memory?lock=33',
                'keywords' => 'ram memory',
            ],
            4 => [
                'id' => 4,
                'name' => 'Samsung 980 Pro 1TB NVMe',
                'category' => 'Storage',
                'description' => '1TB PCIe Gen4 NVMe SSD, blazing fast load times.',
                'price' => 6900.00,
                'image' => 'https://loremflickr.com/600/600/ssd,storage?lock=44',
                'keywords' => 'ssd storage',
            ],
            5 => [
                'id' => 5,
                'name' => 'ASUS B660M Motherboard',
                'category' => 'Motherboard',
                'description' => 'LGA1700 mATX board, PCIe 4.0 ready.',
                'price' => 7400.00,
                'image' => 'https://loremflickr.com/600/600/motherboard?lock=55',
                'keywords' => 'motherboard',
            ],
            6 => [
                'id' => 6,
                'name' => 'Cooler Master 650W PSU',
                'category' => 'Power',
                'description' => '80+ Bronze 650W power supply, reliable and quiet.',
                'price' => 3500.00,
                'image' => 'https://loremflickr.com/600/600/powersupply?lock=66',
                'keywords' => 'power supply',
            ],
        ];
    }

    /** @return array{id:int,name:string,category:string,description:string,price:float,image:string}|null */
    public static function find(int $id): ?array
    {
        return static::items()[$id] ?? null;
    }
}
