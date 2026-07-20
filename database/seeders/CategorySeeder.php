<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Graphics Cards', 'slug' => 'graphics-cards', 'description' => 'NVIDIA and AMD GPUs for gaming and workstation.'],
            ['name' => 'Processors', 'slug' => 'processors', 'description' => 'Intel Core and AMD Ryzen CPUs.'],
            ['name' => 'Motherboards', 'slug' => 'motherboards', 'description' => 'Motherboards for Intel and AMD platforms.'],
            ['name' => 'Memory', 'slug' => 'memory', 'description' => 'DDR4 and DDR5 RAM kits.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
