<?php

// [HAINZ] — original shop model  |  [ESTEBAN] — adapted table/PK/fillable/relationships
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'product_table';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'brand', 'product_name', 'slug', 'description', 'price', 'sale_price',
        'category_id', 'product_image', 'stock', 'sku', 'badge',
        'rating', 'review_count', 'is_active', 'is_featured', 'category',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'product_id', 'product_id');
    }

    public function compatibilities(): HasMany
    {
        return $this->hasMany(ProductCompatibility::class, 'product_id', 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'product_id');
    }

    public function warehouseStock(): HasMany
    {
        return $this->hasMany(WarehouseStock::class, 'product_id', 'product_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->product_image) {
            if (str_starts_with($this->product_image, 'http') || str_starts_with($this->product_image, '/')) {
                return $this->product_image;
            }
            return asset('storage/products/' . $this->product_image);
        }
        return null;
    }
}
