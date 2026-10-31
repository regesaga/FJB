<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'slug',
    'type',
    'filterable',
    'required',
    'searchable',
    'is_recommended',
    'recommendation_group',
    'sort',
];

    protected function casts(): array
{
    return [
        'filterable' => 'boolean',
        'required' => 'boolean',
        'searchable' => 'boolean',
        'is_recommended' => 'boolean',
        'sort' => 'integer',
    ];
}

    protected static function booted(): void
    {
        static::creating(function (ProductAttribute $attribute): void {
            if (empty($attribute->public_id)) {
                $attribute->public_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function options()
    {
        return $this->hasMany(
            ProductAttributeOption::class,
            'product_attribute_id'
        );
    }

    public function categoryAttributes()
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    public function subCategoryAttributes()
    {
        return $this->hasMany(SubCategoryAttribute::class);
    }

    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}