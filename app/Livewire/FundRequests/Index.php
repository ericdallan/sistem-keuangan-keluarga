<?php

namespace App\Livewire\FundRequests;

use App\Models\FundRequest;
use App\Services\FundRequestService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public string $status  = '';
    public string $month   = '';
    public int    $perPage = 10;

    public ?int    $deleteId          = null;
    public string  $deleteDescription = '';

    public ?int    $actionId   = null;
    public string  $actionType = '';

    protected FundRequestService $service;

    public function boot(FundRequestService $service): void
    {
        $this->service = $service;
    }

    protected function queryString(): array
    {
        return [
            'status' => ['except' => ''],
            'month'  => ['except' => ''],
        ];
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }
    public function updatedMonth(): void
    {
        $this->resetPage();
    }

    // ── Delete ────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $fund = $this->service->findOrFail($id);
        $this->authorize('delete', $fund);

        $this->deleteId          = $fund->id;
        $this->deleteDescription = $fund->reason;

        $this->dispatch('open-modal', modal: 'modal-delete-fund');
    }

    public function destroy(): void
    {
        $fund = $this->service->findOrFail($this->deleteId);
        $this->authorize('delete', $fund);

        $this->service->delete($fund);
        $this->dispatch('close-modal', modal: 'modal-delete-fund');
        $this->dispatch('toast', message: 'Pengajuan berhasil dihapus.', type: 'success');
        $this->deleteId = null;
    }

    // ── Approve / Reject ──────────────────────────────────────────

    public function confirmAction(int $id, string $type): void
    {
        $this->authorize('approve', FundRequest::class);
        $this->actionId   = $id;
        $this->actionType = $type;
        $this->dispatch('open-modal', modal: 'modal-action-fund');
    }

    public function executeAction(): void
    {
        $this->authorize('approve', FundRequest::class);
        $fund = $this->service->findOrFail($this->actionId);

        if ($this->actionType === 'approve') {
            $this->service->approve($fund);
            $message = 'Pengajuan disetujui.';
            $type    = 'success';
        } else {
            $this->service->reject($fund);
            $message = 'Pengajuan ditolak.';
            $type    = 'error';
        }

        $this->dispatch('close-modal', modal: 'modal-action-fund');
        $this->dispatch('toast', message: $message, type: $type);
        $this->actionId = null;
    }

    public function render()
    {
        $isAdmin = Auth::user()->role === 'admin';

        $fundRequests = $this->service->getList(
            status: $this->status ?: null,
            month: $this->month  ?: null,
            perPage: $this->perPage,
        );

        return view('livewire.fund-requests.index', [
            'fundRequests' => $fundRequests,
            'isAdmin'      => $isAdmin,
        ])->layout('livewire.layout.app', [
            'title' => $isAdmin ? 'Pengajuan Dana' : 'Pengajuan Dana Saya',
        ]);
    }
}
