<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Admin bisa lihat semua, user hanya miliknya.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->role === 'admin' || $expense->user_id === $user->id;
    }

    public function update(User $user, Expense $expense): bool
    {
        // User hanya bisa edit jika masih pending
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return $user->role === 'admin';
    }

    public function delete(User $user, Expense $expense): bool
    {
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa approve/reject.
     */
    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
