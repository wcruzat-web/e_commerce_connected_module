<?php

// [ESTEBAN] — original model, unchanged
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
