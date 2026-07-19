<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'SHOP20'],
            ['type' => 'discount', 'discount_percentage' => 20, 'max_uses' => null, 'used_count' => 0, 'expires_at' => now()->addYear(), 'is_active' => true]
        );

        Coupon::updateOrCreate(
            ['code' => 'FREESHIP'],
            ['type' => 'free_shipping', 'discount_percentage' => null, 'max_uses' => null, 'used_count' => 0, 'expires_at' => now()->addYear(), 'is_active' => true]
        );
    }
}