<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ESTEBAN — PromoBanner: promo banners for admin management (V2.1)
class PromoBanner extends Model
{
    protected $table = 'promo_banners';
    protected $primaryKey = 'banner_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'subtitle',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
