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

    public function uniqueIds(): array
    {
        return ['uuid_expenses'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
