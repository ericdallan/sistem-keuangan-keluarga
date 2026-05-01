<?php

namespace App\Livewire\Income;

use App\Models\Income;
use App\Services\IncomeService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layout.app')]
#[Title('Pemasukan')]
class Index extends Component
{
    use WithPagination;

    public string $search   = '';
    public string $category = '';
    public string $month    = '';
    public string $year     = '';
    public int    $perPage  = 10;

    public ?int   $deleteId          = null;
    public string $deleteDescription = '';

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

    public function confirmDelete(int $id): void
    {
        $income = $this->service->findOrFail($id);
        $this->deleteId          = $income->id;
        $this->deleteDescription = $income->description;
        $this->dispatch('open-modal', modal: 'modal-delete-income');
    }

    public function destroy(): void
    {
        try {
            $this->service->delete($this->service->findOrFail($this->deleteId));
            $this->dispatch('close-modal', modal: 'modal-delete-income');
            $this->dispatch('toast', message: 'Pemasukan berhasil dihapus.', type: 'success');
        } catch (\RuntimeException $e) {
            $this->dispatch('close-modal', modal: 'modal-delete-income');
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

        $this->deleteId = null;
    }

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
