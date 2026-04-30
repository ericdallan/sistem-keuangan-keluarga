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

    /**
     * Get status badge configuration
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => [
                'bg'    => '#d1e7dd',
                'color' => '#0a5c36',
                'label' => 'Disetujui',
                'icon'  => 'bi-check-circle-fill',
            ],
            'rejected' => [
                'bg'    => '#f8d7da',
                'color' => '#842029',
                'label' => 'Ditolak',
                'icon'  => 'bi-x-circle-fill',
            ],
            default => [
                'bg'    => '#fff3cd',
                'color' => '#664d03',
                'label' => 'Pending',
                'icon'  => 'bi-clock-fill',
            ],
        };
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
