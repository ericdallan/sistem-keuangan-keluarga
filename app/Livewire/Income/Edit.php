<?php

namespace App\Livewire\Income;

use App\Models\Income;
use App\Services\IncomeService;
use Livewire\Component;

/**
 * Komponen Livewire untuk menangani pengeditan data pemasukan.
 */
class Edit extends Component
{
    // ── State (Form Fields) ──────────────────────────────────────
    // Model pemasukan yang sedang diedit
    public Income $income;

    public string $amount      = '';
    public string $description = '';
    public string $date        = '';
    public string $category    = '';

    // Service untuk logika bisnis pemasukan
    protected IncomeService $service;

    /**
     * Inisialisasi service melalui dependency injection.
     */
    public function boot(IncomeService $service): void
    {
        $this->service = $service;
    }

    /**
     * Dijalankan saat komponen dimuat untuk mengisi data awal form.
     * Parameter: Income $income (Model yang akan diedit)
     */
    public function mount(Income $income): void
    {
        // Pastikan user memiliki izin untuk mengedit data ini
        $this->authorize('update', $income);

        $this->income      = $income;
        $this->amount      = (string) $income->amount;
        $this->description = $income->description;
        $this->date        = $income->date->toDateString();
        $this->category    = $income->category;
    }

    /**
     * Mendefinisikan aturan validasi untuk input form.
     */
    protected function rules(): array
    {
        return [
            'amount'      => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'category'    => ['required', 'in:salary,bonus,other'],
        ];
    }

    /**
     * Mendefinisikan pesan error kustom untuk validasi.
     */
    protected function messages(): array
    {
        return [
            'amount.required'      => 'Jumlah wajib diisi.',
            'amount.numeric'       => 'Jumlah harus berupa angka.',
            'amount.min'           => 'Jumlah minimal Rp 1.',
            'description.required' => 'Deskripsi wajib diisi.',
            'date.required'        => 'Tanggal wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
        ];
    }

    /**
     * Menyimpan perubahan data pemasukan ke database.
     */
    public function update(): void
    {
        $this->authorize('update', $this->income);

        $data = $this->validate();

        try {
            $this->service->update($this->income, $data);

            $this->dispatch('toast', message: 'Pemasukan berhasil diperbarui.', type: 'success');
            $this->redirectRoute('income.index', navigate: true);
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    /**
     * Render tampilan komponen.
     */
    public function render()
    {
        return view('livewire.income.edit')
            ->layout('livewire.layout.app', ['title' => 'Edit Pemasukan']);
    }
}
