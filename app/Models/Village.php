<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_code',
        'code',
        'name',
        'postal_code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'district_code' => 'integer',
        'code' => 'string',
    ];

    public function district()
        {
            return $this->belongsTo(
                District::class,
                'district_code',
                'code'
            );
        }
}