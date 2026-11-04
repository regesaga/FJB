<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'online' => 'boolean',
            'birth_date' => 'date',
            'last_seen' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'birth_date',
        'gender',
        'bio',
        'avatar',
        'cover',
        'identity_number',
        'verified',
        'latitude',
        'longitude',
        'last_seen',
        'online',
    ];
    public function user()
        {
            return $this->belongsTo(User::class);
        }
        public function identityVerification()
{
    return $this->hasOne(
        UserIdentityVerification::class,
        'user_id',
        'user_id'
    );
}
}
