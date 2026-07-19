<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
