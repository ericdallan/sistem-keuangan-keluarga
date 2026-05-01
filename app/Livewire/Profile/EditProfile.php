<?php

namespace App\Livewire\Profile;

use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Komponen Livewire untuk halaman profil pengguna.
 * Menangani pembaruan data diri (nama/email) dan perubahan kata sandi (password).
 */
#[Layout('livewire.layout.app')]
#[Title('Profile Pengguna')]
class EditProfile extends Component
{
    // ── Profile fields (Data Diri) ───────────────────────────────
    public string $name  = '';
    public string $email = '';

    // ── Password fields (Keamanan) ───────────────────────────────
    public string $current_password      = '';
    public string $password              = '';
    public string $password_confirmation = '';

    /**
     * Memuat data pengguna saat komponen pertama kali dirender.
     */
    public function mount(): void
    {
        $this->name  = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    // ── Update Profile ───────────────────────────────────────────
    /**
     * Memproses pembaruan nama dan email pengguna.
     * Menggunakan ProfileService untuk logika bisnis dan validasi.
     */
    public function updateProfile(ProfileService $service): void
    {
        $validated = $this->validate(
            $service->profileRules(Auth::id()),
            [],
            ['name' => 'Nama', 'email' => 'Email']
        );

        $emailChanged = $service->updateProfile(Auth::user(), $validated);

        // Jika email diubah, berikan peringatan untuk verifikasi ulang
        if ($emailChanged) {
            $this->dispatch('toast', message: 'Profil diperbarui. Cek email untuk verifikasi.', type: 'warning');
        } else {
            $this->dispatch('toast', message: 'Profil berhasil diperbarui.');
        }
    }

    // ── Update Password ──────────────────────────────────────────
    /**
     * Memproses perubahan password.
     * Memastikan password saat ini valid sebelum memperbarui ke password baru.
     */
    public function updatePassword(ProfileService $service): void
    {
        try {
            $validated = $this->validate(
                $service->passwordRules(),
                [
                    'current_password.current_password' => 'Password saat ini tidak sesuai.',
                    'password.confirmed'                => 'Konfirmasi password tidak cocok.',
                ],
                [
                    'current_password' => 'Password saat ini',
                    'password'         => 'Password baru',
                ]
            );
        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Terjadi kesalahan.', type: 'error');
            throw $e;
        }

        $service->updatePassword(Auth::user(), $validated['password']);

        // Bersihkan input password setelah sukses
        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('toast', message: 'Password berhasil diperbarui.');
    }

    // ── Resend Verification ──────────────────────────────────────
    /**
     * Mengirim ulang tautan verifikasi email jika belum diverifikasi.
     */
    public function resendVerification(ProfileService $service): void
    {
        $service->resendVerification(Auth::user());
        session()->flash('verification-sent', true);
    }

    /**
     * Merender view profil.
     */
    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
