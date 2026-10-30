<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceArea extends Model
{
    protected $fillable = [
        'product_id',
        'village_code',
        'district_code',
        'regency_code',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'village_code',
            'code'
        );
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(
            District::class,
            'district_code',
            'code'
        );
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(
            Regency::class,
            'regency_code',
            'code'
        );
    }
}