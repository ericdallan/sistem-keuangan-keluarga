<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['uuid_incomes', 'user_id', 'amount', 'description', 'date', 'category'])]
class Income extends Model
{
    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid_incomes'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
