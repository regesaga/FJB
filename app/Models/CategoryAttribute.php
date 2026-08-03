<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryAttribute extends Model
{
use HasFactory;

    protected function casts(): array
        {
            return [
                'required' => 'boolean',
                'filterable' => 'boolean',
                'searchable' => 'boolean',
                'visible' => 'boolean',
            ];
        }
    protected $fillable = [
        'category_id',
        'product_attribute_id',
        'required',
        'filterable',
        'searchable',
        'visible',
        'sort',
    ];

    public function category()
{
    return $this->belongsTo(Category::class);
}

public function productAttribute()
{
    return $this->belongsTo(ProductAttribute::class);
}
}
