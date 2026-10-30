<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'regency_code',
        'code',
        'name',
    ];

    protected $casts = [
        'regency_code' => 'string',
        'code' => 'string',
    ];

    public function regency()
    {
        return $this->belongsTo(
            Regency::class,
            'regency_code',
            'code'
        );
    }

    public function villages()
    {
        return $this->hasMany(
            Village::class,
            'district_code',
            'code'
        );
    }
}