<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'warehouse_name',
        'location',
        'sync_status',
        'last_sync_at',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function stock()
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id', 'warehouse_id');
    }
}
