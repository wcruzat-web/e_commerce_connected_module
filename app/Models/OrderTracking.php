<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    protected $table = 'order_tracking';
    protected $primaryKey = 'tracking_id';

    protected $fillable = [
        'order_id',
        'tracking_number',
        'order_status',
        'courier_name',
        'shipped_from',
        'estimated_delivery_date',
        'last_updated',
        'sync_status',
    ];

    protected function casts(): array
    {
        return [
            'estimated_delivery_date' => 'date',
            'last_updated' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
