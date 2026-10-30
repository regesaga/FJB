<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'filterable' => 'boolean',
            'required' => 'boolean',
            'searchable' => 'boolean',
            'sort' => 'integer',
        ];
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

    // Relasi ke subkategori yang menggunakan pertanyaan ini
    public function subCategoryAttributes()
    {
        return $this->hasMany(SubCategoryAttribute::class);
    }

    // Relasi ke jawaban yang menggunakan pertanyaan ini
    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}