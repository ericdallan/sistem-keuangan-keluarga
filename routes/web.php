<?php

use Illuminate\Support\Facades\Route;

// Import Livewire Components
use App\Livewire\Dashboard;
use App\Livewire\Income;
use App\Livewire\Expenses;
use App\Livewire\FundRequests;
use App\Livewire\Users;
use App\Livewire\Statistics;
use App\Livewire\Reports;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {

    // ── Dashboard (Shared) ──────────────────────────────────────
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ── Admin Only ──────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Pemasukan (Hanya Admin)
        Route::prefix('income')->name('income.')->group(function () {
            Route::get('/', Income\Index::class)->name('index');
            Route::get('/create', Income\Create::class)->name('create');
            Route::get('/{income}/edit', Income\Edit::class)->name('edit');
        });

        // Kelola Pengguna
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', Users\Index::class)->name('index');
            Route::get('/create', Users\Create::class)->name('create');
            Route::get('/{user}/edit', Users\Edit::class)->name('edit');
        });
    });

    // ── Shared Features (Logic filter data ada di dalam Class) ──
    // Note: Kita tidak perlu /my-expenses jika class Index bisa mendeteksi role

    // Pengeluaran
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', Expenses\Index::class)->name('index');
        Route::get('/create', Expenses\Create::class)->name('create');
        Route::get('/{expense}/edit', Expenses\Edit::class)->name('edit');
    });

    // Permintaan Dana
    Route::prefix('fund-requests')->name('fund-requests.')->group(function () {
        Route::get('/', FundRequests\Index::class)->name('index');
        Route::get('/create', FundRequests\Create::class)->name('create');
        Route::get('/{fundRequest}/edit', FundRequests\Edit::class)->name('edit');
    });

    // Laporan & Statistik
    // Gunakan satu route, filter datanya di dalam class berdasarkan Auth::user()->role
    Route::get('/statistics', Statistics\Index::class)->name('statistics.index');
    Route::get('/reports', Reports\Index::class)->name('reports.index');

    // ── Profile (Shared) ────────────────────────────────────────
    Route::get('/profile', \App\Livewire\Profile\EditProfile::class)->name('profile.edit');
});

require __DIR__ . '/auth.php';
