<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// HAINZ — ProductCompatibility: compatibility groups for product detail tabs (ERPV1.1)
class ProductCompatibility extends Model
{
    protected $table = 'product_compabilities';
    protected $primaryKey = 'compatibility_id';

    protected $fillable = ['product_id', 'category_name', 'item_name'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
