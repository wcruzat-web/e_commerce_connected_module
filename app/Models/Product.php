<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand', 'name', 'slug', 'description', 'price', 'sale_price',
        'category_id', 'featured_image', 'stock', 'sku', 'badge',
        'rating', 'review_count', 'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
}
