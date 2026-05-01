<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;

class IncomePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Sesuaikan logic-nya:
        // - Semua user yang login bisa create?
        return true;

        // - Atau hanya admin?
        // return $user->role === 'admin';

        // - Atau hanya user tertentu?
        // return $user->hasPermission('income.create');
    }

    public function update(User $user, Income $income): bool
    {
        return $user->role === 'admin' || $user->id === $income->user_id;
    }

    public function delete(User $user, Income $income): bool
    {
        return $user->role === 'admin' || $user->id === $income->user_id;
    }
}
