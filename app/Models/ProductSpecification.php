<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// HAINZ — ProductSpecification: specs for product detail tabs (ERPV1.1)
class ProductSpecification extends Model
{
    protected $primaryKey = 'spec_id';

    protected $fillable = ['product_id', 'category_name', 'label', 'value'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
