<?php
// [CRUZAT] Original Order model (base fields, customer/items/tracking relationships)
// [AGNER]  Added scopeActive, scopeDelivered, isDelivered

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    // [CRUZAT] Original fillable
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'discount',
        'shipping_fee',
        'grand_total',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'notes',
        'payment_status',
        'payment_method',
        'coupon_code',
        'paid_at',
        'customer_received',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    // [CRUZAT]
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // [CRUZAT]
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    // [CRUZAT]
    public function tracking()
    {
        return $this->hasOne(OrderTracking::class, 'order_id', 'order_id');
    }

    // [AGNER] scopes + helper for customer order views
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'Delivered');
    }

    // [AGNER]
    public function scopeDelivered($query)
    {
        return $query->where('status', 'Delivered');
    }

    // [AGNER]
    public function isDelivered(): bool
    {
        return $this->status === 'Delivered';
    }
}
