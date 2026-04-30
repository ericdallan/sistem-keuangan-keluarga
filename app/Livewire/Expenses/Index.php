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

    // Evidence Preview
    public ?string $previewEvidenceUrl = null;
    public ?string $previewEvidenceType = null; // 'image' | 'pdf'

    // Listener untuk event dari admin
    protected function getListeners(): array
    {
        return [
            'expense-status-changed' => 'handleStatusChange',
        ];
    }

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

    public function confirmDelete(string $uuid): void
    {
        // Gunakan where('uuid_expenses', $uuid) untuk mencari data
        $expense = Expense::where('uuid_expenses', $uuid)->firstOrFail();

        $this->authorize('delete', $expense);

        // Tetap simpan ID numerik ke $this->deleteId jika memang 
        // dibutuhkan untuk proses hapus di method destroy()
        $this->deleteId          = $expense->id;
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

    // Preview Evidence
    public function previewEvidence(string $uuid): void
    {
        // Gunakan findByUuid atau query biasa dengan first()
        $expense = Expense::where('uuid_expenses', $uuid)->first();

        if (!$expense) {
            $this->dispatch('toast', message: 'Data pengeluaran tidak ditemukan.', type: 'error');
            return;
        }

        if (!$expense->evidence_path) {
            $this->dispatch('toast', message: 'Bukti tidak ditemukan.', type: 'error');
            return;
        }

        $this->previewEvidenceUrl = asset('storage/' . $expense->evidence_path);
        $ext = strtolower(pathinfo($expense->evidence_path, PATHINFO_EXTENSION));
        $this->previewEvidenceType = in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';

        $this->dispatch('open-modal', modal: 'modal-preview-evidence');
    }

    public function closePreview(): void
    {
        $this->reset('previewEvidenceUrl', 'previewEvidenceType');
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
            $message = 'Pengeluaran disetujui.';
            $type = 'success';
        } else {
            $this->service->reject($expense);
            $message = 'Pengeluaran ditolak.';
            $type = 'error';
        }

        $this->dispatch(
            'expense-status-changed',
            expenseId: $this->actionId,
            userId: $expense->user_id,
            status: $this->actionType
        );

        $this->dispatch('close-modal', modal: 'modal-action-expense');
        $this->dispatch('toast', message: $message, type: $type);
        $this->actionId = null;
    }

    public function handleStatusChange($data = null): void
    {
        if (!is_array($data) || !isset($data['userId'])) {
            return;
        }

        if ((int) $data['userId'] !== Auth::id()) {
            return;
        }

        // 🎯 Toast SEBELUM refresh
        $statusLabel = ($data['status'] ?? '') === 'approve' ? 'disetujui' : 'ditolak';
        $toastType = ($data['status'] ?? '') === 'approve' ? 'success' : 'error';

        $this->dispatch(
            'toast',
            message: "Pengeluaran {$statusLabel} oleh admin.",
            type: $toastType
        );

        // Refresh setelah toast
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $expenses = $this->service->getList(
            search: $this->search ?: null,
            status: $this->status ?: null,
            month: $this->month ? (int) $this->month : null,
            year: $this->year ? (int) $this->year : null,
            perPage: $this->perPage,
        );

        $isAdmin = Auth::user()->role === 'admin';

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'isAdmin' => $isAdmin,
        ])->layout('livewire.layout.app', ['title' => $isAdmin ? 'Pengeluaran' : 'Pengeluaran Saya']);
    }
}
