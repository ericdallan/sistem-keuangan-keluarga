<?php

namespace App\Livewire\Income;

use App\Models\Income;
use App\Services\IncomeService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('livewire.layout.app')]
#[Title('Edit Pemasukan')]
class Edit extends Component
{
    public Income $income;

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

    public function mount(Income $income): void
    {
        $this->income      = $income;
        $this->amount      = (string) $income->amount;
        $this->description = $income->description;
        $this->date        = $income->date->toDateString();
        $this->category    = $income->category;
    }

    public function update(): void
    {
        $this->validate();

        try {
            $this->service->update($this->income, [
                'amount'      => $this->amount,
                'description' => $this->description,
                'date'        => $this->date,
                'category'    => $this->category,
            ]);

            $this->dispatch('toast', message: 'Pemasukan berhasil diperbarui.', type: 'success');
            $this->redirectRoute('income.index', navigate: true);
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.income.edit');
    }
}
