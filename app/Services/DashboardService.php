<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    // DashboardService.php - perbaikan logika balance & chart
    public function getSummary(): array
    {
        $now     = now();
        $month   = $now->month;
        $year    = $now->year;
        $isAdmin = Auth::user()->role === 'admin';
        $userId  = Auth::id();

        $totalIncomeAll   = (float) Income::sum('amount');
        $totalIncomeMonth = (float) Income::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        // Saldo keluarga selalu global — income hanya milik admin,
        // expense dari semua user (yang approved)
        $totalExpenseAllGlobal = (float) Expense::where('status', 'approved')
            ->sum('amount');

        $balance = $totalIncomeAll - $totalExpenseAllGlobal;

        // Card "pengeluaran bulan ini" — personal untuk user, global untuk admin
        $totalExpenseMonth = (float) Expense::where('status', 'approved')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
            ->sum('amount');

        $pendingExpenses     = $isAdmin ? Expense::where('status', 'pending')->count() : 0;
        $pendingFundRequests = $isAdmin ? FundRequest::where('status', 'pending')->count() : 0;

        $recentExpenses = Expense::with('user')
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->take(5)
            ->get();

        $myPendingExpenses = ! $isAdmin
            ? Expense::where('user_id', $userId)->where('status', 'pending')->count()
            : 0;

        return [
            'total_income_all'      => $totalIncomeAll,
            'total_income_month'    => $totalIncomeMonth,
            'total_expense_all'     => $totalExpenseAllGlobal,
            'total_expense_month'   => $totalExpenseMonth,
            'balance'               => $balance,
            'pending_expenses'      => $pendingExpenses,
            'pending_fund_requests' => $pendingFundRequests,
            'recent_expenses'       => $recentExpenses,
            'my_pending_expenses'   => $myPendingExpenses,
            'current_month_label'   => $now->translatedFormat('F Y'),
        ];
    }

    public function getMonthlyChart(): array
    {
        // Chart income selalu global (master admin), expense disesuaikan role
        $isAdmin = Auth::user()->role === 'admin';
        $userId  = Auth::id();
        $months  = [];
        $incomes = [];
        $expenses = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m    = $date->month;
            $y    = $date->year;

            $months[]  = $date->translatedFormat('M Y');
            $incomes[] = (float) Income::whereMonth('date', $m)
                ->whereYear('date', $y)
                ->sum('amount');

            // Admin: semua expense, User: expense global supaya chart bermakna
            // (karena income-nya juga global)
            $expenses[] = (float) Expense::where('status', 'approved')
                ->whereMonth('date', $m)
                ->whereYear('date', $y)
                ->sum('amount');
        }

        return compact('months', 'incomes', 'expenses');
    }
}
