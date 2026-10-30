<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected function casts(): array
        {
            return [
                'filterable' => 'boolean',
                'required' => 'boolean',
                'searchable' => 'boolean',
            ];
        }
    protected $fillable = [
        'name',
        'slug',
        'type',
        'filterable',
        'required',
        'searchable',
        'sort',
    ];

public function options()
{
    return $this->hasMany(
        \App\Models\ProductAttributeOption::class,
        'product_attribute_id'
    );
}
public function categoryAttributes()
{
    return $this->hasMany(CategoryAttribute::class);
}
}
