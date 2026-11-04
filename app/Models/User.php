<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
protected static function booted(): void
{
    static::creating(function (User $user): void {
        if (empty($user->public_id)) {
            $user->public_id = (string) Str::uuid();
        }
    });
}
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
        ];
    }
    public function suspendedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'suspended_by');
}

public function isSuspended(): bool
{
    return $this->suspended_at !== null;
}
public function suspensionLogs(): HasMany
{
    return $this->hasMany(UserSuspensionLog::class);
}

public function performedSuspensionLogs(): HasMany
{
    return $this->hasMany(UserSuspensionLog::class, 'admin_id');
}
    /**
     * Menentukan pengguna yang boleh mengakses Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([
            'Super Admin',
            'Admin',
            'Moderator',
        ]);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function identityVerification()
{
    return $this->hasOne(UserIdentityVerification::class);
}
}