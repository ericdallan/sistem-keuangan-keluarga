<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Model FundRequest (Pengajuan Dana)
 * Mengelola data pengajuan dana dari user, proses persetujuan oleh admin, 
 * serta relasi ke sistem notifikasi.
 */
#[Fillable(['uuid_fund_requests', 'user_id', 'amount', 'reason', 'date', 'month', 'status'])]
class FundRequest extends Model
{
    use HasUuids;

    /**
     * Menentukan kolom yang digunakan untuk UUID.
     */
    public function uniqueIds(): array
    {
        return ['uuid_fund_requests'];
    }

    // ── Relasi ─────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke notifikasi (Polymorphic).
     * Memungkinkan pengajuan ini memiliki notifikasi terkait di tabel Notifications.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ── Konfigurasi UI (Static) ────────────────────────────────────

    /**
     * Mengembalikan konfigurasi gaya visual untuk modul pengajuan dana.
     */
    public static function typeConfig(): array
    {
        return [
            'bg'           => '#cff4fc',
            'color'        => '#055160',
            'icon'         => 'bi-cash-coin',
            'label'        => 'Pengajuan Dana',
            'amount_color' => '#0dcaf0',
        ];
    }

    /**
     * Menentukan konfigurasi badge status berdasarkan status pengajuan.
     * Menggunakan match expression untuk kode yang lebih ringkas dan aman.
     */
    public static function statusBadge(string $status): array
    {
        return match ($status) {
            'approved' => [
                'bg'    => '#d1e7dd',
                'color' => '#0a5c36',
                'icon'  => 'bi-check-circle-fill',
                'label' => 'Disetujui',
            ],
            'rejected' => [
                'bg'    => '#f8d7da',
                'color' => '#842029',
                'icon'  => 'bi-x-circle-fill',
                'label' => 'Ditolak',
            ],
            default => [
                'bg'    => '#fff3cd',
                'color' => '#664d03',
                'icon'  => 'bi-clock-fill',
                'label' => 'Pending',
            ],
        };
    }

    // ── Accessors ──────────────────────────────────────────────────

    /**
     * Mendapatkan konfigurasi badge status untuk instance model saat ini.
     * Cukup panggil $fundRequest->status_badge di file Blade.
     */
    public function getStatusBadgeAttribute(): array
    {
        return static::statusBadge($this->status);
    }
}
