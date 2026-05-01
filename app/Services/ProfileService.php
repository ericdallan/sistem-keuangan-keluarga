<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Service untuk mengelola profil user yang sedang login.
 */
class ProfileService
{
    /**
     * Mengembalikan aturan validasi untuk pembaruan profil (Nama & Email).
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
     * Mengembalikan aturan validasi untuk perubahan password.
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
     * Memperbarui profil user.
     * Mengembalikan status true jika email berubah (perlu verifikasi ulang).
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
     * Mengubah password user menjadi password baru yang telah di-hash.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Mengirim ulang tautan verifikasi email jika belum terverifikasi.
     */
    public function resendVerification(User $user): void
    {
        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }
}
