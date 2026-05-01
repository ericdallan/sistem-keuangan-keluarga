<?php

use Illuminate\Support\Facades\Route;

// ── App Components ───────────────────────────────────────────────
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Income;
use App\Livewire\Expenses;
use App\Livewire\FundRequests;
use App\Livewire\Users;
use App\Livewire\Reports;
use App\Livewire\Profile\EditProfile;

// ── Landing Page ─────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Authenticated & Verified Routes ──────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────
    // Dapat diakses oleh semua role (admin & user)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ── Admin Only ────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Pemasukan (Income) — CRUD
        Route::prefix('income')->name('income.')->group(function () {
            Route::get('/', Income\Index::class)->name('index');
            Route::get('/create', Income\Create::class)->name('create');
            Route::get('/{income}/edit', Income\Edit::class)->name('edit');
        });

        // Kelola Pengguna — hanya list, CRUD via modal
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', Users\Index::class)->name('index');
        });
    });

    // ── User Only ─────────────────────────────────────────────────
    Route::middleware('role:user')->group(function () {

        // Pengeluaran (Expenses) — User: create & edit
        // Index tidak di sini karena admin juga perlu akses (read-only)
        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/create', Expenses\Create::class)->name('create');
            Route::get('/{expense:uuid_expenses}/edit', Expenses\Edit::class)->name('edit');
        });
    });

    // ── Shared (Admin & User) ─────────────────────────────────────

    // Pengeluaran (Expenses) — index diakses semua role
    // Komponen menangani: admin → read + approval, user → CRUD milik sendiri
    Route::get('/expenses', Expenses\Index::class)->name('expenses.index');

    // Pengajuan Dana (Fund Requests) — CRUD
    Route::prefix('fund-requests')->name('fund-requests.')->group(function () {
        Route::get('/', FundRequests\Index::class)->name('index');
        Route::get('/create', FundRequests\Create::class)->name('create');
        Route::get('/{fundRequest}/edit', FundRequests\Edit::class)->name('edit');
    });

    // ── Laporan  ───────────────────────────────────────
    Route::get('/reports', Reports\Index::class)->name('reports.index');

    // ── Profile ───────────────────────────────────────────────────
    Route::get('/profile', EditProfile::class)->name('profile.edit');
});

// ── Auth Routes ───────────────────────────────────────────────────
// Login, register, forgot password, reset password, verify email
require __DIR__ . '/auth.php';
