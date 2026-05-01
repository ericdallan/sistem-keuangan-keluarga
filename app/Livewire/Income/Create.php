<?php

namespace App\Livewire\Income;

use App\Services\IncomeService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('livewire.layout.app')]
#[Title('Tambah Pemasukan')]
class Create extends Component
{
    #[Validate('required|numeric|min:1')]
    public string $amount = '';

    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate('required|date')]
    public string $date = '';

    #[Validate('required|in:salary,bonus,other')]
    public string $category = '';

    protected IncomeService $service;

    public function boot(IncomeService $service): void
    {
        $this->service = $service;
    }

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    public function store(): void
    {
        $this->validate();

        $this->service->store([
            'amount'      => $this->amount,
            'description' => $this->description,
            'date'        => $this->date,
            'category'    => $this->category,
        ]);

        $this->dispatch('toast', message: 'Pemasukan berhasil dicatat.', type: 'success');
        $this->redirectRoute('income.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.income.create');
    }
}
