<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand', 'name', 'slug', 'description', 'price', 'sale_price',
        'category_id', 'featured_image', 'stock', 'sku', 'badge',
        'rating', 'review_count', 'is_active', 'is_featured', 'category',
    ];

    // ESTEBAN — added: $appends for image_url accessor; is_featured, category, badge, sale_price in $fillable; warehouseStock relationship
    protected $appends = ['image_url'];

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
        return $this->hasMany(ProductSpecification::class, 'product_id', 'id');
    }

    public function compatibilities()
    {
        return $this->hasMany(ProductCompatibility::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id');
    }

    public function warehouseStock()
    {
        return $this->hasMany(WarehouseStock::class, 'product_id', 'id');
    }

    // ESTEBAN — changed: uses asset($this->featured_image) instead of asset('storage/' . $this->featured_image) because path already has 'storage/' prefix (V2.3)
    public function getImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return asset($this->featured_image);
        }
        return null;
    }
}
