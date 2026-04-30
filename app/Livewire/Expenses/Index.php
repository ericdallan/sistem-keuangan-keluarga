<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Services\ExpenseService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $month = '';
    public string $year = '';
    public int $perPage = 10;

    // For delete confirmation
    public ?int $deleteId = null;
    public string $deleteDescription = '';

    // For approve/reject confirmation
    public ?int $actionId = null;
    public string $actionType = ''; // 'approve' | 'reject'

    protected ExpenseService $service;

    public function boot(ExpenseService $service): void
    {
        $this->service = $service;
    }

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'status' => ['except' => ''],
            'month'  => ['except' => ''],
            'year'   => ['except' => ''],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedStatus(): void
    {
        $this->resetPage();
    }
    public function updatedMonth(): void
    {
        $this->resetPage();
    }
    public function updatedYear(): void
    {
        $this->resetPage();
    }

    // ── Delete ────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $expense = $this->service->findOrFail($id);
        $this->authorize('delete', $expense);

        $this->deleteId          = $id;
        $this->deleteDescription = $expense->description;
        $this->dispatch('open-modal', modal: 'modal-delete-expense');
    }

    public function destroy(): void
    {
        $expense = $this->service->findOrFail($this->deleteId);
        $this->authorize('delete', $expense);

        $this->service->delete($expense);
        $this->dispatch('close-modal', modal: 'modal-delete-expense');
        $this->dispatch('toast', message: 'Pengeluaran berhasil dihapus.', type: 'success');
        $this->deleteId = null;
    }

    // ── Approve / Reject ──────────────────────────────────────────

    public function confirmAction(int $id, string $type): void
    {
        $this->authorize('approve', Expense::class);
        $this->actionId   = $id;
        $this->actionType = $type;
        $this->dispatch('open-modal', modal: 'modal-action-expense');
    }

    public function executeAction(): void
    {
        $this->authorize('approve', Expense::class);
        $expense = $this->service->findOrFail($this->actionId);

        if ($this->actionType === 'approve') {
            $this->service->approve($expense);
            $this->dispatch('toast', message: 'Pengeluaran disetujui.', type: 'success');
        } else {
            $this->service->reject($expense);
            $this->dispatch('toast', message: 'Pengeluaran ditolak.', type: 'error');
        }

        $this->dispatch('close-modal', modal: 'modal-action-expense');
        $this->actionId = null;
    }

    public function render()
    {
        $expenses = $this->service->getList(
            search: $this->search ?: null,
            status: $this->status ?: null,
            month: $this->month  ? (int) $this->month  : null,
            year: $this->year   ? (int) $this->year   : null,
            perPage: $this->perPage,
        );

        $isAdmin = Auth::user()->role === 'admin';

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'isAdmin'  => $isAdmin,
        ])->layout('livewire.layout.app', ['title' => $isAdmin ? 'Pengeluaran' : 'Pengeluaran Saya']);
    }
}
