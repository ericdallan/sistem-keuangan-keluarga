<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Model Notification untuk mencatat pemberitahuan kepada user.
 * Mendukung notifikasi polymorphic (bisa untuk berbagai jenis model).
 */
#[Fillable(['user_id', 'notifiable_id', 'notifiable_type', 'data', 'read_at'])]
class Notification extends Model
{
    /**
     * Konversi data notifikasi ke array dan waktu baca ke datetime.
     */
    protected function casts(): array
    {
        return [
            'data'    => 'array',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke model yang dipicu (Expense, FundRequest, dll).
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relasi ke User pemilik notifikasi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
