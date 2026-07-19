<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
        'used_at',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}