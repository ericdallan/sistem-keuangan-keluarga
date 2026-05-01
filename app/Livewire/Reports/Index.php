<?php

namespace App\Livewire\Reports;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Komponen Livewire untuk menampilkan Laporan Keuangan.
 * Mengelola filter periode dan pengguna, serta menampilkan data 
 * pemasukan, pengeluaran, dan permintaan dana dengan pagination.
 */
#[Layout('livewire.layout.app')]
#[Title('Laporan Keuangan')]
class Index extends Component
{
    use WithPagination;

    // ── State (Filter) ──────────────────────────────────────────
    public ?string $startMonth = null;
    public ?string $endMonth = null;
    public ?int $selectedUser = null;

    // ── Pagination Settings ─────────────────────────────────────
    public int $incomePerPage = 10;
    public int $expensePerPage = 10;
    public int $fundPerPage = 10;

    // Menambahkan parameter ke URL agar filter tetap ada saat refresh
    protected $queryString = [
        'startMonth' => ['except' => null],
        'endMonth' => ['except' => null],
        'selectedUser' => ['except' => null],
    ];

    /**
     * Inisialisasi state awal (default ke bulan saat ini).
     */
    public function mount()
    {
        $this->startMonth = now()->format('Y-m');
        $this->endMonth = now()->format('Y-m');
    }

    /**
     * Proses render data laporan.
     * Mengambil data dari ReportService dan melakukan query pagination 
     * untuk setiap kategori keuangan.
     */
    public function render(ReportService $service)
    {
        $isAdmin = auth()->user()->role === 'admin';

        // Mengambil data ringkasan laporan dari service
        $report = $service->getReport(
            $this->startMonth,
            $this->endMonth,
            $isAdmin ? $this->selectedUser : null
        );

        $users = $isAdmin ? $service->getUsersForFilter() : [];

        // Penentuan rentang waktu untuk filter database
        $start = \Carbon\Carbon::parse($this->startMonth . '-01')->startOfMonth();
        $end = \Carbon\Carbon::parse($this->endMonth . '-01')->endOfMonth();
        $filterUserId = $isAdmin ? $this->selectedUser : auth()->id();

        // ── Pemasukan (Income) ──────────────────────────────────
        $incomeQuery = \App\Models\Income::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $incomeQuery->where('user_id', $filterUserId);
        }
        $incomes = $incomeQuery->paginate($this->incomePerPage, ['*'], 'incomePage');

        // ── Pengeluaran (Expense) ───────────────────────────────
        $expenseQuery = \App\Models\Expense::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $expenseQuery->where('user_id', $filterUserId);
        }
        $expenses = $expenseQuery->paginate($this->expensePerPage, ['*'], 'expensePage');

        // ── Permintaan Dana (Fund Request) ──────────────────────
        $fundQuery = \App\Models\FundRequest::whereBetween('created_at', [$start, $end])
            ->with('user')
            ->orderByDesc('created_at');
        if ($filterUserId) {
            $fundQuery->where('user_id', $filterUserId);
        }
        $fundRequests = $fundQuery->paginate($this->fundPerPage, ['*'], 'fundPage');

        return view('livewire.reports.index', [
            'report' => $report,
            'users' => $users,
            'isAdmin' => $isAdmin,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'fundRequests' => $fundRequests,
        ])->layout('livewire.layout.app');
    }
}
