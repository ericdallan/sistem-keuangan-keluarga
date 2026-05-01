<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getSummary(): array
    {
        $now     = now();
        $month   = $now->month;
        $year    = $now->year;
        $isAdmin = Auth::user()->role === 'admin';
        $userId  = Auth::id();

        if ($isAdmin) {
            // ── Admin: semua data global ──────────────────────────
            $totalIncomeAll   = (float) Income::sum('amount');
            $totalIncomeMonth = (float) Income::whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $totalExpenseAll = (float) Expense::where('status', 'approved')->sum('amount');
            $balance         = $totalIncomeAll - $totalExpenseAll;

            $totalExpenseMonth = (float) Expense::where('status', 'approved')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $pendingExpenses     = Expense::where('status', 'pending')->count();
            $pendingFundRequests = FundRequest::where('status', 'pending')->count();
            $myPendingExpenses   = 0;
        } else {
            // ── User: data personal ───────────────────────────────
            // Income user = hasil fund request yang diapprove (user_id = user tsb)
            $totalIncomeAll   = (float) Income::where('user_id', $userId)->sum('amount');
            $totalIncomeMonth = (float) Income::where('user_id', $userId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $totalExpenseAll = (float) Expense::where('user_id', $userId)
                ->where('status', 'approved')
                ->sum('amount');

            $balance = $totalIncomeAll - $totalExpenseAll;

            $totalExpenseMonth = (float) Expense::where('user_id', $userId)
                ->where('status', 'approved')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $pendingExpenses     = 0;
            $pendingFundRequests = 0;
            $myPendingExpenses   = Expense::where('user_id', $userId)
                ->where('status', 'pending')
                ->count();
        }

        return [
            'total_income_all'      => $totalIncomeAll,
            'total_income_month'    => $totalIncomeMonth,
            'total_expense_all'     => $totalExpenseAll,
            'total_expense_month'   => $totalExpenseMonth,
            'balance'               => $balance,
            'pending_expenses'      => $pendingExpenses,
            'pending_fund_requests' => $pendingFundRequests,
            'recent_activities'     => $this->getRecentActivities($isAdmin, $userId),
            'my_pending_expenses'   => $myPendingExpenses,
            'current_month_label'   => $now->translatedFormat('F Y'),
        ];
    }

    private function getRecentActivities(bool $isAdmin, int $userId): \Illuminate\Support\Collection
    {
        $items = collect();

        $expenses = Expense::with('user')
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
            ->latest()->take(10)->get()
            ->map(fn($e) => (object) [
                'type'         => 'expense',
                'date'         => $e->date,
                'user_name'    => $e->user->name ?? '-',
                'label'        => $e->description,
                'amount'       => $e->amount,
                'amount_sign'  => '-',
                'amount_color' => '#dc3545',
                'status_badge' => $e->status_badge,
                'icon_bg'      => '#f8d7da',
                'icon_color'   => '#dc3545',
                'icon'         => 'bi-arrow-up-circle',
                'type_label'   => 'Pengeluaran',
                'sorted_at'    => $e->created_at,
            ]);

        $funds = FundRequest::with('user')
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
            ->latest()->take(10)->get()
            ->map(fn($f) => (object) [
                'type'         => 'fund_request',
                'date'         => $f->created_at->toDateString(),
                'user_name'    => $f->user->name ?? '-',
                'label'        => $f->reason,
                'amount'       => $f->amount,
                'amount_sign'  => '+',
                'amount_color' => '#0dcaf0',
                'status_badge' => $this->fundBadge($f->status),
                'icon_bg'      => '#cff4fc',
                'icon_color'   => '#055160',
                'icon'         => 'bi-cash-coin',
                'type_label'   => 'Pengajuan Dana',
                'sorted_at'    => $f->created_at,
            ]);

        $items = $items->merge($expenses)->merge($funds);

        if ($isAdmin) {
            $incomes = Income::with('user')
                ->latest()->take(10)->get()
                ->map(fn($i) => (object) [
                    'type'         => 'income',
                    'date'         => $i->date,
                    'user_name'    => $i->user->name ?? 'Admin',
                    'label'        => $i->description,
                    'amount'       => $i->amount,
                    'amount_sign'  => '+',
                    'amount_color' => '#198754',
                    'status_badge' => ['bg' => '#d1e7dd', 'color' => '#198754', 'icon' => 'bi-check-circle-fill', 'label' => 'Tercatat'],
                    'icon_bg'      => '#d1e7dd',
                    'icon_color'   => '#198754',
                    'icon'         => 'bi-arrow-down-circle',
                    'type_label'   => 'Pemasukan',
                    'sorted_at'    => $i->created_at,
                ]);

            $items = $items->merge($incomes);
        }

        return $items->sortByDesc('sorted_at')->take(8)->values();
    }

    private function fundBadge(string $status): array
    {
        return match ($status) {
            'approved' => ['bg' => '#d1e7dd', 'color' => '#198754', 'icon' => 'bi-check-circle-fill', 'label' => 'Disetujui'],
            'rejected' => ['bg' => '#f8d7da', 'color' => '#dc3545', 'icon' => 'bi-x-circle-fill',     'label' => 'Ditolak'],
            default    => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-clock-fill',         'label' => 'Pending'],
        };
    }

    public function getMonthlyChart(): array
    {
        $isAdmin = Auth::user()->role === 'admin';
        $userId  = Auth::id();
        $months  = [];
        $incomes = [];
        $expenses = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m    = $date->month;
            $y    = $date->year;

            $months[] = $date->translatedFormat('M Y');

            $incomes[] = (float) Income::when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
                ->whereMonth('date', $m)->whereYear('date', $y)->sum('amount');

            $expenses[] = (float) Expense::where('status', 'approved')
                ->when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
                ->whereMonth('date', $m)->whereYear('date', $y)->sum('amount');
        }

        return compact('months', 'incomes', 'expenses');
    }
}
