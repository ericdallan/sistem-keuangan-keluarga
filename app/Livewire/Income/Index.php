<?php

namespace App\Livewire\Income;

use App\Models\Income;
use App\Services\IncomeService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Properti filter dan pencarian
    public string $search   = '';
    public string $category = '';
    public string $month    = '';
    public string $year     = '';
    public int    $perPage  = 10;

    // Properti untuk manajemen hapus (delete)
    // UUID = string, bukan int
    public ?string $deleteId          = null;
    public string  $deleteDescription = '';

    protected IncomeService $service;

    public function boot(IncomeService $service): void
    {
        $this->service = $service;
    }

    protected function queryString(): array
    {
        return [
            'search'   => ['except' => ''],
            'category' => ['except' => ''],
            'month'    => ['except' => ''],
            'year'     => ['except' => ''],
        ];
    }

    // --- Hooks Update ---

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedCategory(): void
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

    /**
     * Menyiapkan modal konfirmasi penghapusan data.
     */
    public function confirmDelete(string $id): void  // ← string, bukan int
    {
        $income = $this->service->findOrFail($id);
        $this->authorize('delete', $income);

        $this->deleteId          = $income->uuid_incomes ?? $income->id;
        $this->deleteDescription = $income->description;

        $this->dispatch('open-modal', modal: 'modal-delete-income');
    }

    /**
     * Mengeksekusi penghapusan data setelah konfirmasi.
     */
    public function destroy(): void
    {
        try {
            $income = $this->service->findOrFail($this->deleteId);
            $this->authorize('delete', $income);

            $this->service->delete($income);

            $this->dispatch('close-modal', modal: 'modal-delete-income');
            $this->dispatch('toast', message: 'Pemasukan berhasil dihapus.', type: 'success');
        } catch (\RuntimeException $e) {
            $this->dispatch('close-modal', modal: 'modal-delete-income');
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

        $this->deleteId = null;
    }

    /**
     * Render daftar pemasukan.
     */
    public function render()
    {
        $incomes = $this->service->getList(
            search: $this->search   ?: null,
            category: $this->category ?: null,
            month: $this->month    ?: null,
            year: $this->year     ?: null,
            perPage: $this->perPage,
        );

        return view('livewire.income.index', [
            'incomes' => $incomes,
        ])->layout('livewire.layout.app', ['title' => 'Pemasukan']);
    }
}
