<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'district_code',
        'code',
        'name',
        'postal_code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'district_code' => 'string',
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