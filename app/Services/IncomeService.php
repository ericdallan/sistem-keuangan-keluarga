<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class IncomeService
{
    /**
     * List pemasukan dengan filter.
     * Hanya admin yang bisa mengakses.
     */
    public function getList(
        ?string $search   = null,
        ?string $category = null,
        ?string $month    = null,
        ?int    $year     = null,
        int     $perPage  = 10,
    ): LengthAwarePaginator {
        return Income::with('user')
            ->when($search,   fn($q) => $q->where('description', 'like', "%{$search}%"))
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($month,    fn($q) => $q->whereMonth('date', $month))
            ->when($year,     fn($q) => $q->whereYear('date', $year))
            ->latest('date')
            ->paginate($perPage);
    }

    /**
     * Ambil single income by ID atau UUID.
     */
    public function findOrFail(int|string $id): Income
    {
        if (is_numeric($id)) {
            return Income::with('user')->findOrFail($id);
        }

        return Income::with('user')
            ->where('uuid_incomes', $id)
            ->firstOrFail();
    }
    
    /**
     * Buat pemasukan baru (oleh admin).
     */
    public function store(array $data): Income
    {
        $income = Income::create([
            'user_id'     => Auth::id(),
            'amount'      => $data['amount'],
            'description' => $data['description'],
            'date'        => $data['date'],
            'category'    => $data['category'],
        ]);

        $this->notifyAdmins($income);

        return $income;
    }

    /**
     * Update pemasukan.
     */
    public function update(Income $income, array $data): Income
    {
        if (!$income->is_mutable) {
            throw new \RuntimeException('Pemasukan dari pengajuan dana tidak dapat diubah.');
        }

        $income->update([
            'amount'      => $data['amount'],
            'description' => $data['description'],
            'date'        => $data['date'],
            'category'    => $data['category'],
        ]);

        return $income->fresh();
    }


    /**
     * Hapus pemasukan.
     */
    public function delete(Income $income): void
    {
        if (!$income->is_mutable) {
            throw new \RuntimeException('Pemasukan dari pengajuan dana tidak dapat dihapus.');
        }
        $income->delete();
    }

    /**
     * Total pemasukan semua waktu.
     */
    public function totalAll(): int
    {
        return (int) Income::sum('amount');
    }

    /**
     * Total pemasukan bulan & tahun tertentu.
     */
    public function totalByMonth(int $month, int $year): int
    {
        return (int) Income::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    // ── Private helpers ───────────────────────────────────────────

    private function notifyAdmins(Income $income): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'         => $admin->id,
                'notifiable_id'   => $income->id,
                'notifiable_type' => Income::class,
                'data' => [
                    'icon'  => 'bi-arrow-down-circle',
                    'color' => 'text-success',
                    'title' => 'Pemasukan Baru Dicatat',
                    'body'  => 'Rp ' . number_format($income->amount, 0, ',', '.') . ' — ' . $income->description,
                    'url'   => route('income.index'),
                ],
            ]);
        }
    }
}
