<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductAttributeOption extends Model
{
    protected $fillable = [
        'product_attribute_id',
        'value',
        'sort',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProductAttributeOption $option): void {
            if (empty($option->public_id)) {
                $option->public_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}