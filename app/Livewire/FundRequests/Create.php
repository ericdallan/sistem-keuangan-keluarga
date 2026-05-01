<?php

namespace App\Livewire\FundRequests;

use App\Services\FundRequestService;
use Livewire\Component;

/**
 * Komponen Livewire untuk menangani pembuatan pengajuan dana baru.
 */
class Create extends Component
{
    public string $amount = '';
    public string $reason = '';
    public string $month  = '';

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
     * Dijalankan saat komponen pertama kali dimuat.
     */
    public function mount(): void
    {
        // Default bulan ke bulan saat ini (format Y-m)
        $this->month = now()->format('Y-m');
    }

    /**
     * Mendefinisikan aturan validasi untuk input form.
     */
    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
            'month'  => ['required', 'date_format:Y-m'],
        ];
    }

    /**
     * Mendefinisikan pesan error kustom untuk validasi.
     */
    protected function messages(): array
    {
        return [
            'amount.required'   => 'Jumlah wajib diisi.',
            'amount.numeric'    => 'Jumlah harus berupa angka.',
            'amount.min'        => 'Jumlah minimal Rp 1.',
            'reason.required'   => 'Alasan wajib diisi.',
            'reason.max'        => 'Alasan maksimal 255 karakter.',
            'month.required'    => 'Bulan wajib diisi.',
            'month.date_format' => 'Format bulan tidak valid.',
        ];
    }

    /**
     * Menyimpan data pengajuan dana ke database.
     */
    public function save(): void
    {
        // Otorisasi apakah user diizinkan membuat pengajuan
        $this->authorize('create', \App\Models\FundRequest::class);

        // Validasi input
        $data = $this->validate();

        // Proses penyimpanan melalui service
        $this->service->store($data);

        // Kirim notifikasi dan arahkan kembali ke daftar pengajuan
        $this->dispatch('toast', message: 'Pengajuan dana berhasil dikirim.', type: 'success');
        $this->redirectRoute('fund-requests.index', navigate: true);
    }

    /**
     * Render tampilan komponen.
     */
    public function render()
    {
        return view('livewire.fund-requests.create')
            ->layout('livewire.layout.app', ['title' => 'Tambah Pengajuan Dana']);
    }
}
