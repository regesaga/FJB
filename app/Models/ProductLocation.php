<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLocation extends Model
{
    protected $fillable = [
        'product_id',
        'province_code',
        'regency_code',
        'district_code',
        'village_code',
        'address_detail',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'province_code' => 'string',
            'regency_code' => 'string',
            'district_code' => 'string',
            'village_code' => 'string',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function province()
    {
        return $this->belongsTo(
            Province::class,
            'province_code',
            'code'
        );
    }

    public function regency()
    {
        return $this->belongsTo(
            Regency::class,
            'regency_code',
            'code'
        );
    }

    public function district()
    {
        return $this->belongsTo(
            District::class,
            'district_code',
            'code'
        );
    }

    public function village()
    {
        return $this->belongsTo(
            Village::class,
            'village_code',
            'code'
        );
    }
}