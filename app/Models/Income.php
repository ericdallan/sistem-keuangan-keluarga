<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Income (Pemasukan)
 * Mengelola data pemasukan dana ke dalam sistem keuangan.
 */
#[Fillable(['uuid_incomes', 'user_id', 'amount', 'description', 'date', 'category'])]
class Income extends Model
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
     * Menentukan kolom yang digunakan untuk UUID.
     */
    public function uniqueIds(): array
    {
        return ['uuid_incomes'];
    }

    /**
     * Relasi ke User pemilik pemasukan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Konfigurasi UI (Static) ────────────────────────────────────

    /**
     * Mengembalikan konfigurasi gaya visual untuk modul pemasukan.
     */
    public static function typeConfig(): array
    {
        return [
            'bg'           => '#d1e7dd',
            'color'        => '#198754',
            'icon'         => 'bi-arrow-down-circle',
            'label'        => 'Pemasukan',
            'amount_color' => '#198754',
        ];
    }

    /**
     * Menentukan konfigurasi badge berdasarkan KATEGORI pemasukan.
     */
    public static function categoryBadge(string $category): array
    {
        return match ($category) {
            'salary'       => ['bg' => '#d1e7dd', 'color' => '#0a5c36', 'icon' => 'bi-briefcase', 'label' => 'Gaji'],
            'bonus'        => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-star', 'label' => 'Bonus'],
            'fund_request' => ['bg' => '#cff4fc', 'color' => '#055160', 'icon' => 'bi-cash-coin', 'label' => 'Pengajuan Dana'],
            default        => ['bg' => '#e2e3e5', 'color' => '#383d41', 'icon' => 'bi-tag', 'label' => 'Lainnya'],
        };
    }

    /**
     * Menentukan konfigurasi badge berdasarkan Status pemasukan.
     */
    public static function statusBadge(string $status): array
    {
        return [
            'bg'    => '#d1e7dd',
            'color' => '#0a5c36',
            'icon'  => 'bi-check-circle-fill',
            'label' => 'Tercatat',
        ];
    }
    // ── Accessors ──────────────────────────────────────────────────

    /**
     * Mendapatkan badge kategori untuk instance model.
     * Cukup panggil $income->category_badge di file Blade.
     */
    public function getCategoryBadgeAttribute(): array
    {
        return static::categoryBadge($this->category);
    }

    /**
     * Memformat nominal angka ke format mata uang Rupiah.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Income dari fund_request tidak bisa diedit maupun dihapus.
     */
    public function getIsMutableAttribute(): bool
    {
        return $this->category !== 'fund_request';
    }
}
