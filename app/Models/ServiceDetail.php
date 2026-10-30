<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetail extends Model
{
    protected $fillable = [
        'product_id',
        'pricing_type',
        'price_from',
        'price_to',
        'price_unit',
        'experience_years',
        'schedule',
        'service_description',
    ];

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'price_to' => 'decimal:2',
            'experience_years' => 'integer',
            'schedule' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}