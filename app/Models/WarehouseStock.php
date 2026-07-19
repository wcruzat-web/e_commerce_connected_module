<?php

// [ESTEBAN] — original model, unchanged
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';
    protected $primaryKey = 'warehouse_stock_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
