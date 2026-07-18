<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $primaryKey = 'spec_id';

    protected $fillable = ['product_id', 'category_name', 'attribute_name', 'attribute_value'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
