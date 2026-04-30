<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Hanya user (bukan admin) yang bisa membuat pengeluaran.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * Admin bisa lihat semua, user hanya miliknya.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->role === 'admin' || $expense->user_id === $user->id;
    }

    /**
     * User hanya bisa edit miliknya sendiri & status pending.
     * Admin tidak bisa edit sama sekali.
     */
    public function update(User $user, Expense $expense): bool
    {
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return false;
    }

    /**
     * User hanya bisa hapus miliknya sendiri & status pending.
     * Admin tidak bisa hapus sama sekali.
     */
    public function delete(User $user, Expense $expense): bool
    {
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return false;
    }

    /**
     * Hanya admin yang bisa approve/reject.
     */
    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
