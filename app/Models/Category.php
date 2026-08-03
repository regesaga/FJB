<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'image',
        'sort',
        'is_active',
    ];

    /**
     * Relasi ke Sub Category
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * Relasi ke Dynamic Attribute
     */
    public function categoryAttributes()
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    /**
     * Relasi ke Product
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}