<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserIdentityVerification extends Model
{
    protected $fillable = [
        'user_id',
        'identity_type',
        'identity_number_encrypted',
        'identity_document_path',
        'selfie_path',
        'face_match_status',
        'liveness_status',
        'status',
        'rejection_reason',
        'verified_by',
        'submitted_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            // Laravel mengenkripsi otomatis saat menyimpan
            'identity_number_encrypted' => 'encrypted',

            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    // Pemilik identitas
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Admin yang memeriksa identitas
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}