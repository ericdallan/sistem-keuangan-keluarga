<?php

namespace App\Policies;

use App\Models\FundRequest;
use App\Models\User;

class FundRequestPolicy
{
    public function view(User $user, FundRequest $fundRequest): bool
    {
        return $user->role === 'admin' || $fundRequest->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    public function update(User $user, FundRequest $fundRequest): bool
    {
        if ($user->role === 'user') {
            return $fundRequest->user_id === $user->id && $fundRequest->status === 'pending';
        }

        return $user->role === 'admin';
    }

    public function delete(User $user, FundRequest $fundRequest): bool
    {
        if ($user->role === 'user') {
            return $fundRequest->user_id === $user->id && $fundRequest->status === 'pending';
        }

        return $user->role === 'admin';
    }

    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
