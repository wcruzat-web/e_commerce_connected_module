<?php

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
        'product_name',
        'sku',
        'brand',
        'category',
        'price',
        'stock',
        'product_image',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'product_id', 'product_id');
    }

    public function compatibility(): HasMany
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
            return asset('storage/products/' . $this->product_image);
        }
        return null;
    }
}
