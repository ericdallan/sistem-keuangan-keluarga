<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['uuid_expenses', 'user_id', 'amount', 'description', 'date', 'evidence_path', 'status'])]
class Expense extends Model
{
    use HasUuids;

    protected $casts = [
        'date'   => 'date',
        'amount' => 'integer',
    ];

    public function getRouteKey(): string
    {
        return $this->uuid_expenses;
    }

    public function uniqueIds(): array
    {
        return ['uuid_expenses'];
    }

    // ── Relations ─────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ── Accessors ─────────────────────────────────────────────────

    public static function typeConfig(): array
    {
        return [
            'bg'    => '#f8d7da',
            'color' => '#dc3545',
            'icon'  => 'bi-arrow-up-circle',
            'label' => 'Pengeluaran',
            'amount_color' => '#dc3545',
        ];
    }

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
     
    /**
     * Get status badge configuration (instance accessor)
     */
    public function getStatusBadgeAttribute(): array
    {
        return static::statusBadge($this->status);
    }
    
    /**
     * Check if expense is editable (only pending)
     */
    public function getIsEditableAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if expense is approved
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if expense is rejected
     */
    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get formatted amount with Rp prefix
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get evidence URL or null
     */
    public function getEvidenceUrlAttribute(): ?string
    {
        return $this->evidence_path
            ? asset('storage/' . $this->evidence_path)
            : null;
    }

    /**
     * Get evidence file type (image/pdf)
     */
    public function getEvidenceTypeAttribute(): ?string
    {
        if (!$this->evidence_path) return null;

        $ext = strtolower(pathinfo($this->evidence_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';
    }
}
