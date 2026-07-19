<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'discount_percentage',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function isFreeShipping(): bool
    {
        return $this->type === 'free_shipping';
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'free_shipping') {
            return 0;
        }

        return round($subtotal * ($this->discount_percentage / 100), 2);
    }
}