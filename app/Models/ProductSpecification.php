<?php

// [HAINZ] — original  |  [ESTEBAN] — added $table = 'product_specification'
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specification';
    protected $primaryKey = 'spec_id';

    protected $fillable = ['product_id', 'category_name', 'label', 'value'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
