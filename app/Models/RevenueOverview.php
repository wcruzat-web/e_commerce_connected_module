<?php

// [ESTEBAN] — original model, unchanged
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueOverview extends Model
{
    protected $table = 'revenue_overview';
    protected $primaryKey = 'revenue_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'month_label',
        'overview_year',
        'revenue_amount',
    ];

    protected $casts = [
        'overview_year' => 'integer',
        'revenue_amount' => 'decimal:2',
    ];
}
