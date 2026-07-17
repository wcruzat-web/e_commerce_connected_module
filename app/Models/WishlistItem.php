<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WishlistItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'wishlist_item_id';

    protected $fillable = [
        'wishlist_id', 'customer_id', 'product_id', 'product_name',
        'product_description', 'product_image', 'unit_price', 'quantity', 'in_stock',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'in_stock' => 'boolean',
    ];

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class, 'wishlist_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
