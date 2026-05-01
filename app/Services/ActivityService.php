<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service untuk mengelola data aktivitas keuangan (Income, Expense, FundRequest).
 * Bertanggung jawab menyatukan data dari berbagai tabel berbeda ke dalam satu format
 * yang konsisten agar mudah ditampilkan di dashboard.
 */
class ActivityService
{
    /**
     * Mengambil data aktivitas gabungan (Income, Expense, FundRequest) dengan pagination.
     *
     * @param string $search Kata kunci pencarian.
     * @param string $type Jenis aktivitas (expense, fund_request, income).
     * @param string $month Filter bulan (format Y-m).
     * @param int $perPage Jumlah item per halaman.
     * @return LengthAwarePaginator
     */
    public function getActivities(string $search, string $type, string $month, int $perPage): LengthAwarePaginator
    {
        $isAdmin = Auth::user()->role === 'admin';
        $userId  = Auth::id();
        $queries = collect();

        // 1. Tambahkan query Pengeluaran jika tipe cocok
        if ($type === '' || $type === 'expense') {
            $queries->push($this->buildExpenseQuery($isAdmin, $userId, $search, $month));
        }

        // 2. Tambahkan query Pengajuan Dana jika tipe cocok
        if ($type === '' || $type === 'fund_request') {
            $queries->push($this->buildFundRequestQuery($isAdmin, $userId, $search, $month));
        }

        // 3. Tambahkan query Pemasukan (Hanya Admin yang bisa lihat)
        if ($isAdmin && ($type === '' || $type === 'income')) {
            $queries->push($this->buildIncomeQuery($search, $month));
        }

        // Jika tidak ada data, kembalikan paginator kosong
        if ($queries->isEmpty()) {
            return new LengthAwarePaginator([], 0, $perPage);
        }

        // Gabungkan semua query menjadi satu menggunakan UnionAll
        $base = $queries->first();
        foreach ($queries->slice(1) as $q) {
            $base = $base->unionAll($q);
        }

        // Jalankan query union dan lakukan pagination
        return DB::table(DB::raw("({$base->toSql()}) as activities"))
            ->mergeBindings($base)
            ->orderByDesc('sorted_at')
            ->paginate($perPage);
    }

    /**
     * Membangun query untuk data Expenses (Pengeluaran).
     */
    private function buildExpenseQuery($isAdmin, $userId, $search, $month)
    {
        return DB::table('expenses')
            ->join('users', 'users.id', '=', 'expenses.user_id')
            ->select(
                DB::raw("'expense' as type"),
                DB::raw("'Pengeluaran' as type_label"),
                'expenses.id',
                'expenses.created_at as sorted_at',
                'expenses.date',
                'users.name as user_name',
                'expenses.description as label',
                'expenses.amount',
                DB::raw("'-' as amount_sign"),
                'expenses.status'
            )
            ->when(! $isAdmin, fn($q) => $q->where('expenses.user_id', $userId))
            ->when($search, fn($q) => $q->where('expenses.description', 'like', "%{$search}%"))
            ->when($month, fn($q) => $q->whereRaw("DATE_FORMAT(expenses.date, '%Y-%m') = ?", [$month]));
    }

    /**
     * Membangun query untuk data Fund Requests (Pengajuan Dana).
     */
    private function buildFundRequestQuery($isAdmin, $userId, $search, $month)
    {
        return DB::table('fund_requests')
            ->join('users', 'users.id', '=', 'fund_requests.user_id')
            ->select(
                DB::raw("'fund_request' as type"),
                DB::raw("'Pengajuan Dana' as type_label"),
                'fund_requests.id',
                'fund_requests.created_at as sorted_at',
                'fund_requests.created_at as date',
                'users.name as user_name',
                'fund_requests.reason as label',
                'fund_requests.amount',
                DB::raw("'+' as amount_sign"),
                'fund_requests.status'
            )
            ->when(! $isAdmin, fn($q) => $q->where('fund_requests.user_id', $userId))
            ->when($search, fn($q) => $q->where('fund_requests.reason', 'like', "%{$search}%"))
            ->when($month, fn($q) => $q->whereRaw("DATE_FORMAT(fund_requests.created_at, '%Y-%m') = ?", [$month]));
    }

    /**
     * Membangun query untuk data Incomes (Pemasukan).
     */
    private function buildIncomeQuery($search, $month)
    {
        return DB::table('incomes')
            ->join('users', 'users.id', '=', 'incomes.user_id')
            ->select(
                DB::raw("'income' as type"),
                DB::raw("'Pemasukan' as type_label"),
                'incomes.id',
                'incomes.created_at as sorted_at',
                'incomes.date',
                'users.name as user_name',
                'incomes.description as label',
                'incomes.amount',
                DB::raw("'+' as amount_sign"),
                DB::raw("'approved' as status")
            )
            ->when($search, fn($q) => $q->where('incomes.description', 'like', "%{$search}%"))
            ->when($month, fn($q) => $q->whereRaw("DATE_FORMAT(incomes.date, '%Y-%m') = ?", [$month]));
    }
}
