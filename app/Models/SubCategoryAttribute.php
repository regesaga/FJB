<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::creating(function (SubCategoryAttribute $mapping): void {
            if (empty($mapping->public_id)) {
                $mapping->public_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
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