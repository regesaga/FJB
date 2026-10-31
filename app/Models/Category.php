<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'slug',
    'listing_type',
    'icon',
    'image',
    'sort',
    'is_active',
];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Menggunakan UUID untuk URL, bukan ID angka.
     */
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /**
     * Membuat UUID otomatis ketika kategori dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (empty($category->public_id)) {
                $category->public_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke Sub Category.
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * Relasi ke Dynamic Attribute.
     */
    public function categoryAttributes()
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    /**
     * Relasi ke Product.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}