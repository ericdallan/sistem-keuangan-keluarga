<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function getReport(?string $startMonth = null, ?string $endMonth = null, ?int $userId = null): array
    {
        $isAdmin = Auth::user()->role === 'admin';
        $currentUserId = Auth::id();

        // Default: bulan ini
        $startMonth = $startMonth ?? now()->format('Y-m');
        $endMonth = $endMonth ?? $startMonth;

        $start = Carbon::parse($startMonth . '-01')->startOfMonth();
        $end = Carbon::parse($endMonth . '-01')->endOfMonth();

        // Label range
        if ($startMonth === $endMonth) {
            $monthLabel = $start->translatedFormat('F Y');
        } else {
            $monthLabel = $start->translatedFormat('M Y') . ' - ' . $end->translatedFormat('M Y');
        }

        $filterUserId = $isAdmin ? $userId : $currentUserId;

        // Pemasukan
        $incomeQuery = Income::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $incomeQuery->where('user_id', $filterUserId);
        }
        $totalIncome = (float) (clone $incomeQuery)->sum('amount');

        // Pengeluaran
        $expenseQuery = Expense::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $expenseQuery->where('user_id', $filterUserId);
        }
        $totalExpense = (float) (clone $expenseQuery)->where('status', 'approved')->sum('amount');
        $totalPending = (float) (clone $expenseQuery)->where('status', 'pending')->sum('amount');
        $totalRejected = (float) (clone $expenseQuery)->where('status', 'rejected')->sum('amount');

        // Pengajuan Dana
        $fundQuery = FundRequest::whereBetween('created_at', [$start, $end])
            ->with('user')
            ->orderByDesc('created_at');
        if ($filterUserId) {
            $fundQuery->where('user_id', $filterUserId);
        }
        $totalFundRequested = (float) $fundQuery->sum('amount');
        $totalFundApproved = (float) (clone $fundQuery)->where('status', 'approved')->sum('amount');

        // Saldo akumulasi
        $accumulatedIncome = (float) Income::when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))->sum('amount');
        $accumulatedExpense = (float) Expense::when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))->where('status', 'approved')->sum('amount');
        $balance = $accumulatedIncome - $accumulatedExpense;

        // Top Users
        $showTopUsers = $isAdmin && !$filterUserId;
        $topUsers = $showTopUsers ? $this->getTopUsers($start, $end, $currentUserId) : [];

        return [
            'start_month' => $startMonth,
            'end_month' => $endMonth,
            'month_label' => $monthLabel,
            'show_top_users' => $showTopUsers,
            'show_income_detail' => $isAdmin,
            'summary' => [
                'balance' => $balance,
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'total_expense_pending' => $totalPending,
                'total_expense_rejected' => $totalRejected,
                'fund_requested' => $totalFundRequested,
                'fund_approved' => $totalFundApproved,
            ],
            'top_users' => $topUsers,
        ];
    }

    private function getTopUsers(Carbon $start, Carbon $end, int $excludeUserId): array
    {
        $users = User::select('id', 'name')
            ->where('id', '!=', $excludeUserId)
            ->get();

        $result = [];

        foreach ($users as $user) {
            $expenseCount = Expense::where('user_id', $user->id)
                ->whereBetween('date', [$start, $end])
                ->count();

            $expenseAmount = (float) Expense::where('user_id', $user->id)
                ->whereBetween('date', [$start, $end])
                ->where('status', 'approved')
                ->sum('amount');

            $fundCount = FundRequest::where('user_id', $user->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $fundAmount = (float) FundRequest::where('user_id', $user->id)
                ->whereBetween('created_at', [$start, $end])
                ->where('status', 'approved')
                ->sum('amount');

            if ($expenseCount > 0 || $fundCount > 0) {
                $result[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'expense_count' => $expenseCount,
                    'expense_amount' => $expenseAmount,
                    'fund_count' => $fundCount,
                    'fund_amount' => $fundAmount,
                    'total_activity' => $expenseCount + $fundCount,
                    'total_amount' => $expenseAmount + $fundAmount,
                ];
            }
        }

        return collect($result)
            ->sortByDesc('total_amount')
            ->take(5)
            ->values()
            ->toArray();
    }

    public function getUsersForFilter(): array
    {
        return User::select('id', 'name', 'role')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}
