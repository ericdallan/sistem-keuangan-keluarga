<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Model Expense (Pengeluaran)
 * Mengelola data transaksi pengeluaran, status persetujuan, dan lampiran bukti transaksi.
 */
#[Fillable(['uuid_expenses', 'user_id', 'amount', 'description', 'date', 'evidence_path', 'status'])]
class Expense extends Model
{
    use HasUuids;

    /**
     * Konversi tipe data otomatis untuk kolom tertentu.
     */
    protected $casts = [
        'date'   => 'date',
        'amount' => 'integer',
    ];

    /**
     * Menggunakan UUID sebagai route key agar URL lebih aman dan tidak mudah ditebak.
     */
    public function getRouteKey(): string
    {
        return $this->uuid_expenses;
    }

    /**
     * Menentukan kolom mana yang menggunakan UUID.
     */
    public function uniqueIds(): array
    {
        return ['uuid_expenses'];
    }

    // ── Relasi ─────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ── Konfigurasi UI (Static) ────────────────────────────────────

    /**
     * Mengembalikan konfigurasi gaya visual untuk modul pengeluaran.
     */
    public static function typeConfig(): array
    {
        return [
            'bg'           => '#f8d7da',
            'color'        => '#dc3545',
            'icon'         => 'bi-arrow-up-circle',
            'label'        => 'Pengeluaran',
            'amount_color' => '#dc3545',
        ];
    }

    /**
     * Menentukan konfigurasi badge status (pending, approved, rejected).
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
     
    // ── Accessors (Penyederhana Logic di Blade) ────────────────────

    /**
     * Mendapatkan konfigurasi badge status untuk instance model.
     */
    public function getStatusBadgeAttribute(): array
    {
        return static::statusBadge($this->status);
    }

    /**
     * Mengecek apakah pengeluaran bisa diedit (hanya status 'pending').
     */
    public function getIsEditableAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mengecek apakah pengeluaran sudah disetujui.
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Mengecek apakah pengeluaran ditolak.
     */
    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Memformat nominal angka ke format mata uang Rupiah.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Mendapatkan URL lengkap untuk file bukti transaksi.
     */
    public function getEvidenceUrlAttribute(): ?string
    {
        return $this->evidence_path
            ? asset('storage/' . $this->evidence_path)
            : null;
    }

    /**
     * Mendeteksi jenis file bukti (gambar atau PDF).
     */
    public function getEvidenceTypeAttribute(): ?string
    {
        if (!$this->evidence_path) return null;

        $ext = strtolower(pathinfo($this->evidence_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';
    }
}
