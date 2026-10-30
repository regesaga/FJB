<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'category_id',
        'sub_category_id',
        'title',
        'slug',
        'description',
        'price',
        'price_type',
        'condition',
        'status',
        'views',
        'published_at',

        // Informasi listing
        'listing_type',
        'availability_status',
        'stock_quantity',
        'availability_confirmed_at',
        'availability_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'views' => 'integer',
            'stock_quantity' => 'integer',
            'published_at' => 'datetime',
            'availability_confirmed_at' => 'datetime',
            'availability_expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }

    public function location()
    {
        return $this->hasOne(ProductLocation::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    // Relasi khusus jasa
    public function serviceDetail()
    {
        return $this->hasOne(ServiceDetail::class);
    }

    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }
}