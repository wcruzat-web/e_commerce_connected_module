<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ESTEBAN — WarehouseStock model: pivot between warehouses and products (V2.9)
class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';
    protected $primaryKey = 'warehouse_stock_id';

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
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
