<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['uuid_fund_requests', 'user_id', 'amount', 'reason', 'date', 'month', 'status'])]
class FundRequest extends Model
{
    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid_fund_requests'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
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
}
