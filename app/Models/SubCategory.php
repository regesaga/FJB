<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'icon',
        'sort',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relasi ke pengaturan pertanyaan berdasarkan subkategori
    public function subCategoryAttributes()
    {
        return $this->hasMany(SubCategoryAttribute::class);
    }
}