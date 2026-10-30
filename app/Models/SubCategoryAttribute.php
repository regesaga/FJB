<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategoryAttribute extends Model
{
    protected $fillable = [
        'sub_category_id',
        'product_attribute_id',
        'listing_type',
        'required',
        'filterable',
        'searchable',
        'visible',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'filterable' => 'boolean',
            'searchable' => 'boolean',
            'visible' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}