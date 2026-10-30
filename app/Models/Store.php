<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Store extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'is_open' => 'boolean',
            'rating' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
    protected static function booted(): void
{
    static::creating(function (Store $store): void {
        if (empty($store->public_id)) {
            $store->public_id = (string) Str::uuid();
        }
    });
}
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo',
        'banner',
        'description',
        'phone',
        'whatsapp',
        'address',
        'latitude',
        'longitude',
        'rating',
        'total_reviews',
        'followers',
        'verified',
        'is_open',
    ];
        public function user()
            {
                return $this->belongsTo(User::class);
            }

    public function products()
            {
                return $this->hasMany(Product::class);
            }

            /**
 * Store Model
 *
 * @property int $id
 * @property string $name
 */
}
