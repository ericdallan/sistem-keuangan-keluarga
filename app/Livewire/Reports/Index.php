<?php

namespace App\Livewire\Reports;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layout.app')]
#[Title('Laporan Keuangan')]
class Index extends Component
{
    use WithPagination;

    public ?string $startMonth = null;
    public ?string $endMonth = null;
    public ?int $selectedUser = null;

    public int $incomePerPage = 10;
    public int $expensePerPage = 10;
    public int $fundPerPage = 10;

    protected $queryString = [
        'startMonth' => ['except' => null],
        'endMonth' => ['except' => null],
        'selectedUser' => ['except' => null],
    ];

    public function mount()
    {
        $this->startMonth = now()->format('Y-m');
        $this->endMonth = now()->format('Y-m');
    }

    public function render(ReportService $service)
    {
        $isAdmin = auth()->user()->role === 'admin';

        $report = $service->getReport(
            $this->startMonth,
            $this->endMonth,
            $isAdmin ? $this->selectedUser : null
        );

        $users = $isAdmin ? $service->getUsersForFilter() : [];

        // Paginated queries
        $start = \Carbon\Carbon::parse($this->startMonth . '-01')->startOfMonth();
        $end = \Carbon\Carbon::parse($this->endMonth . '-01')->endOfMonth();
        $filterUserId = $isAdmin ? $this->selectedUser : auth()->id();

        // Income paginated
        $incomeQuery = \App\Models\Income::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $incomeQuery->where('user_id', $filterUserId);
        }
        $incomes = $incomeQuery->paginate($this->incomePerPage, ['*'], 'incomePage');

        // Expense paginated
        $expenseQuery = \App\Models\Expense::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $expenseQuery->where('user_id', $filterUserId);
        }
        $expenses = $expenseQuery->paginate($this->expensePerPage, ['*'], 'expensePage');

        // Fund Request paginated
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
