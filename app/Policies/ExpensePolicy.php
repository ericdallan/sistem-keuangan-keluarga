<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

/**
 * Policy untuk mengelola otorisasi pada transaksi pengeluaran.
 * Menerapkan kontrol akses ketat untuk menjaga integritas data keuangan.
 */
class ExpensePolicy
{
    /**
     * Hanya User (anggota keluarga/karyawan) yang diperbolehkan membuat pengeluaran.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * Admin berhak melihat semua data. User hanya bisa melihat pengeluaran miliknya sendiri.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->role === 'admin' || $expense->user_id === $user->id;
    }

    /**
     * User hanya bisa mengupdate data miliknya jika statusnya masih 'pending'.
     * Admin tidak diizinkan mengubah pengeluaran (menjaga akurasi data yang diajukan).
     */
    public function update(User $user, Expense $expense): bool
    {
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return false;
    }

    /**
     * User hanya bisa menghapus data miliknya jika statusnya masih 'pending'.
     * Admin tidak diizinkan menghapus data pengeluaran (untuk tujuan pelacakan).
     */
    public function delete(User $user, Expense $expense): bool
    {
        if ($user->role === 'user') {
            return $expense->user_id === $user->id && $expense->status === 'pending';
        }

        return false;
    }

    /**
     * Hanya Admin yang memiliki otoritas untuk menyetujui (approve) atau menolak (reject) pengeluaran.
     */
    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
