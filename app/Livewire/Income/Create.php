<?php

namespace App\Livewire\Income;

use App\Models\Income;
use App\Services\IncomeService;
use Livewire\Component;

/**
 * Komponen Livewire untuk formulir pembuatan data pemasukan.
 * Menangani validasi input dan penyimpanan data melalui service.
 */
class Create extends Component
{
    // ── State (Form Fields) ──────────────────────────────────────
    public string $amount      = '';
    public string $description = '';
    public string $date        = '';
    public string $category    = '';

    protected IncomeService $service;

    /**
     * Injeksi Service ke dalam komponen saat booting.
     */
    public function boot(IncomeService $service): void
    {
        $this->service = $service;
    }

    /**
     * Inisialisasi data default saat komponen dimuat.
     */
    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    /**
     * Definisi aturan validasi untuk input form.
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
     * Kustomisasi pesan error validasi.
     */
    protected function messages(): array
    {
        return [
            'amount.required'      => 'Jumlah wajib diisi.',
            'amount.numeric'       => 'Jumlah harus berupa angka.',
            'amount.min'           => 'Jumlah minimal Rp 1.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max'      => 'Deskripsi maksimal 255 karakter.',
            'date.required'        => 'Tanggal wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
        ];
    }

    /**
     * Proses penyimpanan data pemasukan.
     */
    public function store(): void
    {
        $this->authorize('create', Income::class);

        $data = $this->validate();

        $this->service->store($data);

        $this->dispatch('toast', message: 'Pemasukan berhasil dicatat.', type: 'success');
        $this->redirectRoute('income.index', navigate: true);
    }

    /**
     * Merender view form tambah pemasukan.
     */
    public function render()
    {
        return view('livewire.income.create')
            ->layout('livewire.layout.app', ['title' => 'Tambah Pemasukan']);
    }
}
