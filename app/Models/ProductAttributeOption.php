<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeOption extends Model
{
    protected $fillable = [
        'product_attribute_id',
        'value',
        'sort',
    ];
    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}
