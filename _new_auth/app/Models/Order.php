<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id', 'status', 'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /** Active shipments (not yet delivered) → shown in "My Orders". */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'Delivered');
    }

    /** Delivered orders → shown in "Order History". */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'Delivered');
    }

    public function isDelivered(): bool
    {
        return $this->status === 'Delivered';
    }
}
