<?php

use Illuminate\Support\Facades\Route;

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
        Route::prefix('income')->name('income.')->group(function () {
            Route::get('/', Income\Index::class)->name('index');
            Route::get('/create', Income\Create::class)->name('create');
            Route::get('/{income}/edit', Income\Edit::class)->name('edit');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', Users\Index::class)->name('index');
        });
    });

    // ── Shared Features ─────────────────────────────────────────
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', Expenses\Index::class)->name('index');
        Route::get('/create', Expenses\Create::class)->name('create');
        Route::get('/{expense}/edit', Expenses\Edit::class)->name('edit');
    });

    Route::prefix('fund-requests')->name('fund-requests.')->group(function () {
        Route::get('/', FundRequests\Index::class)->name('index');
        Route::get('/create', FundRequests\Create::class)->name('create');
        Route::get('/{fundRequest}/edit', FundRequests\Edit::class)->name('edit');
    });

    // ── User Routes (Alias) ─────────────────────────────────────
    // ⭐ PINDAH KE DALAM auth group!
    Route::middleware('role:user')->group(function () {
        Route::get('/my-expenses', Expenses\Index::class)->name('my-expenses.index');
        Route::get('/my-fund-requests', FundRequests\Index::class)->name('my-fund-requests.index');
    });

    // ── Laporan & Statistik ─────────────────────────────────────
    Route::get('/statistics', Statistics\Index::class)->name('statistics.index');
    Route::get('/reports', Reports\Index::class)->name('reports.index');

    // ── Profile ─────────────────────────────────────────────────
    Route::get('/profile', \App\Livewire\Profile\EditProfile::class)->name('profile.edit');
});

require __DIR__ . '/auth.php';
