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

    // ── State (Filter) ──────────────────────────────────────────
    public ?string $startMonth = null;
    public ?string $endMonth = null;
    public ?int $selectedUser = null;

    // ── Export Modal State ──────────────────────────────────────
    public bool $showExportModal = false;
    public bool $showPreviewModal = false;
    public string $exportFormat = 'pdf';
    public ?string $exportStartMonth = null;
    public ?string $exportEndMonth = null;

    // ── Filter Kategori ─────────────────────────────────────────
    public array $selectedCategories = ['income', 'expense', 'fund'];
    public ?int $exportSelectedUser = null;

    // ── Pagination Settings ─────────────────────────────────────
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
        $now = now()->format('Y-m');
        $this->startMonth = $now;
        $this->endMonth = $now;
        $this->exportStartMonth = $now;
        $this->exportEndMonth = $now;
    }

    // ── Export Modal Methods ─────────────────────────────────────
    public function openExportModal()
    {
        $this->exportStartMonth = $this->startMonth;
        $this->exportEndMonth = $this->endMonth;
        $this->exportSelectedUser = $this->selectedUser;
        $this->selectedCategories = ['income', 'expense', 'fund'];
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->showPreviewModal = false;
        $this->exportFormat = 'pdf';
    }

    public function getExportLabel(): string
    {
        $start = \Carbon\Carbon::parse($this->exportStartMonth . '-01');
        $end = \Carbon\Carbon::parse($this->exportEndMonth . '-01');

        if ($this->exportStartMonth === $this->exportEndMonth) {
            return $start->translatedFormat('F Y');
        }
        return $start->translatedFormat('M Y') . ' - ' . $end->translatedFormat('M Y');
    }

    public function previewPdf()
    {
        $this->validate([
            'exportStartMonth' => 'required|date_format:Y-m',
            'exportEndMonth' => 'required|date_format:Y-m|after_or_equal:exportStartMonth',
            'selectedCategories' => 'required|array|min:1',
        ]);

        $this->showPreviewModal = true;
    }

    public function downloadExport()
    {
        $this->validate([
            'exportStartMonth' => 'required|date_format:Y-m',
            'exportEndMonth' => 'required|date_format:Y-m|after_or_equal:exportStartMonth',
            'selectedCategories' => 'required|array|min:1',
        ]);

        $isAdmin = auth()->user()->role === 'admin';
        $userId = $isAdmin ? $this->exportSelectedUser : auth()->id();

        $this->dispatch('triggerDownload', [
            'format' => $this->exportFormat,
            'start' => $this->exportStartMonth,
            'end' => $this->exportEndMonth,
            'user' => $userId,
            'categories' => $this->selectedCategories,
        ]);

        $this->closeExportModal();
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

        $start = \Carbon\Carbon::parse($this->startMonth . '-01')->startOfMonth();
        $end = \Carbon\Carbon::parse($this->endMonth . '-01')->endOfMonth();
        $filterUserId = $isAdmin ? $this->selectedUser : auth()->id();

        // ── Pemasukan ──────────────────────────────────────────
        $incomeQuery = \App\Models\Income::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $incomeQuery->where('user_id', $filterUserId);
        }
        $incomes = $incomeQuery->paginate($this->incomePerPage, ['*'], 'incomePage');

        // ── Pengeluaran ───────────────────────────────────────────
        $expenseQuery = \App\Models\Expense::whereBetween('date', [$start, $end])
            ->with('user')
            ->orderByDesc('date');
        if ($filterUserId) {
            $expenseQuery->where('user_id', $filterUserId);
        }
        $expenses = $expenseQuery->paginate($this->expensePerPage, ['*'], 'expensePage');

        // ── Permintaan Dana ──────────────────────────────────────
        $fundQuery = \App\Models\FundRequest::whereBetween('created_at', [$start, $end])
            ->with('user')
            ->orderByDesc('created_at');
        if ($filterUserId) {
            $fundQuery->where('user_id', $filterUserId);
        }
        $fundRequests = $fundQuery->paginate($this->fundPerPage, ['*'], 'fundPage');

        // ── Data untuk Preview ─────────────────────────────────
        $previewData = null;
        if ($this->showPreviewModal) {
            $previewService = new \App\Services\ReportExportService();
            $previewUserId = $isAdmin ? $this->exportSelectedUser : auth()->id();
            $previewData = $previewService->getPreviewData(
                $this->exportStartMonth,
                $this->exportEndMonth,
                $previewUserId,
                $this->selectedCategories
            );
        }

        return view('livewire.reports.index', [
            'report' => $report,
            'users' => $users,
            'isAdmin' => $isAdmin,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'fundRequests' => $fundRequests,
            'previewData' => $previewData,
        ])->layout('livewire.layout.app');
    }
}
