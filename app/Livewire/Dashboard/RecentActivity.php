<?php

namespace App\Livewire\Dashboard;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RecentActivity extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $type    = '';   // expense | fund_request | income
    public string $month   = '';   // format Y-m
    public int    $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedType(): void
    {
        $this->resetPage();
    }
    public function updatedMonth(): void
    {
        $this->resetPage();
    }

    private function isAdmin(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public function render()
    {
        $isAdmin = $this->isAdmin();
        $userId  = Auth::id();

        // ── Build each sub-query ──────────────────────────────────

        $expenseQ = DB::table('expenses')
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
                'expenses.status',
            )
            ->when(! $isAdmin, fn($q) => $q->where('expenses.user_id', $userId))
            ->when($this->search, fn($q) => $q->where('expenses.description', 'like', "%{$this->search}%"))
            ->when($this->month,  fn($q) => $q->whereRaw("DATE_FORMAT(expenses.date, '%Y-%m') = ?", [$this->month]));

        $fundQ = DB::table('fund_requests')
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
                'fund_requests.status',
            )
            ->when(! $isAdmin, fn($q) => $q->where('fund_requests.user_id', $userId))
            ->when($this->search, fn($q) => $q->where('fund_requests.reason', 'like', "%{$this->search}%"))
            ->when($this->month,  fn($q) => $q->whereRaw("DATE_FORMAT(fund_requests.created_at, '%Y-%m') = ?", [$this->month]));

        // ── Apply type filter ─────────────────────────────────────

        $queries = collect();

        if ($this->type === '' || $this->type === 'expense') {
            $queries->push($expenseQ);
        }

        if ($this->type === '' || $this->type === 'fund_request') {
            $queries->push($fundQ);
        }

        if ($isAdmin && ($this->type === '' || $this->type === 'income')) {
            $incomeQ = DB::table('incomes')
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
                    DB::raw("'approved' as status"),
                )
                ->when($this->search, fn($q) => $q->where('incomes.description', 'like', "%{$this->search}%"))
                ->when($this->month,  fn($q) => $q->whereRaw("DATE_FORMAT(incomes.date, '%Y-%m') = ?", [$this->month]));

            $queries->push($incomeQ);
        }

        // ── Union & paginate ──────────────────────────────────────

        if ($queries->isEmpty()) {
            $activities = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage);
        } else {
            $base = $queries->first();
            foreach ($queries->slice(1) as $q) {
                $base = $base->unionAll($q);
            }

            $activities = DB::table(DB::raw("({$base->toSql()}) as activities"))
                ->mergeBindings($base)
                ->orderByDesc('sorted_at')
                ->paginate($this->perPage);
        }

        return view('livewire.dashboard.recent-activity', [
            'activities' => $activities,
            'isAdmin'    => $isAdmin,
        ]);
    }
}
