<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Actions\Logout;

// ── Guest Only ────────────────────────────────────────────────────
// Hanya bisa diakses sebelum login
Route::middleware('guest')->group(function () {

    // Halaman login
    Route::get('login', Login::class)->name('login');

    // Halaman registrasi
    Route::get('register', Register::class)->name('register');

    // Lupa password — request link reset
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');

    // Reset password via link dari email
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// ── Authenticated Only ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Verifikasi email — tampil setelah register
    Route::get('verify-email', VerifyEmail::class)->name('verification.notice');

    // Handler klik link verifikasi dari email
    Route::get('verify-email/{id}/{hash}', function (
        \Illuminate\Foundation\Auth\EmailVerificationRequest $request
    ) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    // Konfirmasi password untuk area sensitif
    Route::get('confirm-password', ConfirmPassword::class)->name('password.confirm');

    // Logout
    Route::post('logout', Logout::class)->name('logout');
});
