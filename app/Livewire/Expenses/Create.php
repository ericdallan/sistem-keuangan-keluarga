<?php

namespace App\Livewire\Expenses;

use App\Services\ExpenseService;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Komponen Livewire untuk formulir pembuatan data pengeluaran (Expense).
 * Menangani input data, validasi file bukti transaksi, dan penyimpanan melalui service.
 */
class Create extends Component
{
    use WithFileUploads; // Trait untuk menangani upload file

    // ── State (Form Fields) ──────────────────────────────────────
    public string $amount       = '';
    public string $description  = '';
    public string $date         = '';
    public $evidence            = null; // Properti untuk menampung file upload

    protected ExpenseService $service;

    /**
     * Injeksi Service ke dalam komponen saat booting.
     */
    public function boot(ExpenseService $service): void
    {
        $this->service = $service;
    }

    /**
     * Definisi aturan validasi untuk input form.
     */
    protected function rules(): array
    {
        return [
            'amount'      => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date', 'before_or_equal:today'],
            'evidence'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * Kustomisasi pesan error validasi agar lebih ramah pengguna.
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
            'date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'evidence.mimes'       => 'Bukti harus berupa file JPG, PNG, atau PDF.',
            'evidence.max'         => 'Ukuran file maksimal 2 MB.',
        ];
    }

    /**
     * Proses penyimpanan data pengeluaran.
     * Melakukan otorisasi, validasi, dan memanggil service untuk store data.
     */
    public function save(): void
    {
        // Otorisasi: Pastikan pengguna memiliki izin menambah pengeluaran
        $this->authorize('create', \App\Models\Expense::class);

        $data = $this->validate();

        // Menyimpan data melalui Service
        $this->service->store($data, $this->evidence);

        // Notifikasi dan navigasi setelah sukses
        $this->dispatch('toast', message: 'Pengeluaran berhasil ditambahkan.', type: 'success');
        $this->redirectRoute('expenses.index', navigate: true);
    }

    /**
     * Merender view form tambah pengeluaran.
     */
    public function render()
    {
        return view('livewire.expenses.create')
            ->layout('livewire.layout.app', ['title' => 'Tambah Pengeluaran']);
    }
}
