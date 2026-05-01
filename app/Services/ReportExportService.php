<?php

namespace App\Services;

use App\Exports\ReportExport;
use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportService
{
    public function exportPdf(string $startMonth, string $endMonth, ?int $userId = null, array $categories = ['income', 'expense', 'fund'])
    {
        $data = $this->getExportData($startMonth, $endMonth, $userId, $categories);

        $pdf = Pdf::loadView('exports.report-pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = $this->buildFilename($startMonth, $endMonth, $userId, $categories, 'pdf');

        return $pdf->download($filename);
    }


    public function exportExcel(string $startMonth, string $endMonth, ?int $userId = null, array $categories = ['income', 'expense', 'fund'])
    {
        $filename = $this->buildFilename($startMonth, $endMonth, $userId, $categories, 'xlsx');

        return Excel::download(
            new ReportExport($startMonth, $endMonth, $userId, $categories),
            $filename
        );
    }
    
    private function buildFilename(string $startMonth, string $endMonth, ?int $userId, array $categories, string $ext): string
    {
        $start = Carbon::parse($startMonth . '-01');
        $end   = Carbon::parse($endMonth . '-01');

        // Periode
        $period = $startMonth === $endMonth
            ? $start->translatedFormat('F-Y')
            : $start->translatedFormat('F-Y') . '_sd_' . $end->translatedFormat('F-Y');

        // User
        $userPart = 'Semua-Pengguna';
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                // Bersihkan nama: spasi → tanda hubung, hapus karakter aneh
                $userPart = preg_replace('/[^a-zA-Z0-9\-]/', '', str_replace(' ', '-', $user->name));
            }
        }

        // Kategori
        $allCategories = ['income', 'expense', 'fund'];
        $categoryMap   = [
            'income'  => 'Pemasukan',
            'expense' => 'Pengeluaran',
            'fund'    => 'Pengajuan',
        ];

        sort($categories);
        sort($allCategories);

        $categoryPart = $categories === $allCategories
            ? 'Semua-Kategori'
            : implode('-', array_map(fn($c) => $categoryMap[$c] ?? $c, $categories));

        // Format: Laporan-Keuangan_Mei-2026_Semua-Pengguna_Semua-Kategori.pdf
        return "Laporan-Keuangan_{$period}_{$userPart}_{$categoryPart}.{$ext}";
    }


    public function getPreviewData(string $startMonth, string $endMonth, ?int $userId = null, array $categories = ['income', 'expense', 'fund']): array
    {
        return $this->getExportData($startMonth, $endMonth, $userId, $categories);
    }

    private function getExportData(string $startMonth, string $endMonth, ?int $userId, array $categories): array
    {
        $isAdmin = Auth::user()->role === 'admin';
        $filterUserId = $isAdmin ? $userId : Auth::id();

        $start = Carbon::parse($startMonth . '-01')->startOfMonth();
        $end = Carbon::parse($endMonth . '-01')->endOfMonth();

        $incomes = collect();
        $expenses = collect();
        $fundRequests = collect();

        $totalIncome = 0;
        $totalExpense = 0;
        $totalPending = 0;
        $totalFund = 0;

        if (in_array('income', $categories)) {
            $query = Income::whereBetween('date', [$start, $end])->with('user');
            if ($filterUserId) $query->where('user_id', $filterUserId);
            $incomes = $query->orderByDesc('date')->get();
            $totalIncome = (float) (clone $query)->sum('amount');
        }

        if (in_array('expense', $categories)) {
            $query = Expense::whereBetween('date', [$start, $end])->with('user');
            if ($filterUserId) $query->where('user_id', $filterUserId);
            $expenses = $query->orderByDesc('date')->get();
            $totalExpense = (float) (clone $query)->where('status', 'approved')->sum('amount');
            $totalPending = (float) (clone $query)->where('status', 'pending')->sum('amount');
        }

        if (in_array('fund', $categories)) {
            $query = FundRequest::whereBetween('created_at', [$start, $end])->with('user');
            if ($filterUserId) $query->where('user_id', $filterUserId);
            $fundRequests = $query->orderByDesc('created_at')->get();
            $totalFund = (float) (clone $query)->sum('amount');
        }

        $filterUserName = null;
        if ($userId) {
            $user = User::find($userId);
            $filterUserName = $user?->name;
        }

        return [
            'start' => $start,
            'end' => $end,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'fundRequests' => $fundRequests,
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'total_pending' => $totalPending,
                'total_fund' => $totalFund,
            ],
            'categories' => $categories,
            'filterUserName' => $filterUserName,
        ];
    }
}
