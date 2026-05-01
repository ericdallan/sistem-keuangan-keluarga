<?php

namespace App\Policies;

use App\Models\FundRequest;
use App\Models\User;

/**
 * Policy untuk mengelola otorisasi pada pengajuan dana.
 * Menentukan hak akses user terhadap aksi (view, create, update, delete, approve).
 */
class FundRequestPolicy
{
    /**
     * Memastikan user hanya bisa melihat pengajuannya sendiri, kecuali Admin.
     */
    public function view(User $user, FundRequest $fundRequest): bool
    {
        return $user->role === 'admin' || $fundRequest->user_id === $user->id;
    }

    /**
     * Hanya User yang diizinkan untuk membuat pengajuan dana.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * User hanya bisa mengupdate pengajuan miliknya sendiri dan selama statusnya masih 'pending'.
     * Admin memiliki akses penuh untuk melakukan update.
     */
    public function update(User $user, FundRequest $fundRequest): bool
    {
        if ($user->role === 'user') {
            return $fundRequest->user_id === $user->id && $fundRequest->status === 'pending';
        }

        return $user->role === 'admin';
    }

    /**
     * User hanya bisa menghapus pengajuan miliknya sendiri dan selama statusnya masih 'pending'.
     */
    public function delete(User $user, FundRequest $fundRequest): bool
    {
        if ($user->role === 'user') {
            return $fundRequest->user_id === $user->id && $fundRequest->status === 'pending';
        }

        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang diizinkan untuk menyetujui pengajuan dana.
     */
    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
