<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    /**
     * Validation rules untuk update profile info.
     */
    public function profileRules(int $userId): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
        ];
    }

    /**
     * Validation rules untuk update password.
     */
    public function passwordRules(): array
    {
        return [
            'current_password'      => ['required', 'string', 'current_password'],
            'password'              => ['required', 'string', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * Update nama & email user.
     * Return true jika email berubah (perlu re-verify).
     */
    public function updateProfile(User $user, array $validated): bool
    {
        $emailChanged = $user->email !== $validated['email'];

        $user->fill([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $emailChanged;
    }

    /**
     * Update password user.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Kirim ulang email verifikasi.
     */
    public function resendVerification(User $user): void
    {
        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }
}
