<?php

namespace App\Livewire\FundRequests;

use App\Models\FundRequest;
use App\Services\FundRequestService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

/**
 * Komponen Livewire untuk menampilkan daftar pengajuan dana dengan fitur
 * pencarian, filter, paginasi, hapus, serta persetujuan/penolakan.
 */
class Index extends Component
{
    use WithPagination;

    // Properti filter dan pencarian
    public string $search  = '';
    public string $status  = '';
    public string $month   = '';
    public int    $perPage = 10;

    // Properti untuk manajemen hapus (delete)
    public ?string $deleteId          = null;
    public string  $deleteDescription = '';

    // Properti untuk manajemen aksi (approve/reject)
    public ?string  $actionId   = null;
    public string   $actionType = '';

    /** @var FundRequestService Service untuk logika bisnis pengajuan dana */
    protected FundRequestService $service;

    /**
     * Inisialisasi service melalui dependency injection.
     */
    public function boot(FundRequestService $service): void
    {
        $this->service = $service;
    }

    /**
     * Mengonfigurasi parameter URL agar dapat dibagikan (searchable).
     */
    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'status' => ['except' => ''],
            'month'  => ['except' => ''],
        ];
    }

    // --- Hooks Update ---

    public function updatedStatus(): void
    {
        $this->resetPage();
    }
    public function updatedMonth(): void
    {
        $this->resetPage();
    }
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ── Delete ────────────────────────────────────────────────────

    /**
     * Menyiapkan modal konfirmasi penghapusan data.
     */
    public function confirmDelete(string $uuid): void
    {
        $fund = $this->service->findOrFail($uuid);
        $this->authorize('delete', $fund);

        $this->deleteId          = $fund->uuid_fund_requests;
        $this->deleteDescription = $fund->reason;

        $this->dispatch('open-modal', modal: 'modal-delete-fund');
    }

    /**
     * Mengeksekusi penghapusan data setelah konfirmasi.
     */
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

    /**
     * Menyiapkan modal konfirmasi untuk persetujuan atau penolakan.
     */
    public function confirmAction(string $uuid, string $type): void
    {
        $this->authorize('approve', FundRequest::class);
        $this->actionId   = $uuid;
        $this->actionType = $type;
        $this->dispatch('open-modal', modal: 'modal-action-fund');
    }

    /**
     * Mengeksekusi aksi persetujuan atau penolakan berdasarkan tipe.
     */
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

    /**
     * Render daftar pengajuan dana.
     */
    public function render()
    {
        $isAdmin = Auth::user()->isAdmin();

        $fundRequests = $this->service->getList(
            search: $this->search ?: null,
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
