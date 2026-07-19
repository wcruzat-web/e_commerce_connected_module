<?php
// [CRUZAT] Original OrderItem model (order_id, product_id, quantity, unit_price, subtotal)
// [AGNER]  Added customer_id, product_name, product_image to fillable
// [ESTEBAN] FK local key updated to 'product_id'

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';

    // [CRUZAT] Original: order_id, product_id, quantity, unit_price, subtotal
    // [AGNER]  Added: customer_id, product_name, product_image
    protected $fillable = [
        'order_id',
        'customer_id',
        'product_id',
        'product_name',
        'product_image',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // [CRUZAT]
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // [CRUZAT]
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
