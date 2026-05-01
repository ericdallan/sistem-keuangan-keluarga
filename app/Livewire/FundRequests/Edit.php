<?php

namespace App\Livewire\FundRequests;

use App\Models\FundRequest;
use App\Services\FundRequestService;
use Livewire\Component;

/**
 * Komponen Livewire untuk menangani pengeditan pengajuan dana yang ada.
 */
class Edit extends Component
{
    /** @var FundRequest Model pengajuan dana yang sedang diedit */
    public FundRequest $fundRequest;

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
     * Dijalankan saat komponen dimuat untuk mengisi data awal form.
     * * @param FundRequest $fundRequest Model yang akan diedit
     */
    public function mount(FundRequest $fundRequest): void
    {
        // Pastikan user memiliki izin untuk mengedit data ini
        $this->authorize('update', $fundRequest);

        $this->fundRequest = $fundRequest;
        $this->amount      = (string) $fundRequest->amount;
        $this->reason      = $fundRequest->reason;
        $this->month       = $fundRequest->month;
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
     * Menyimpan perubahan data pengajuan dana ke database.
     */
    public function save(): void
    {
        // Otorisasi ulang sebelum menyimpan perubahan
        $this->authorize('update', $this->fundRequest);

        // Validasi input
        $data = $this->validate();

        // Update data melalui service
        $this->service->update($this->fundRequest, $data);

        // Kirim notifikasi dan arahkan kembali ke daftar pengajuan
        $this->dispatch('toast', message: 'Pengajuan dana berhasil diperbarui.', type: 'success');
        $this->redirectRoute('fund-requests.index', navigate: true);
    }

    /**
     * Render tampilan komponen.
     */
    public function render()
    {
        return view('livewire.fund-requests.edit')
            ->layout('livewire.layout.app', ['title' => 'Edit Pengajuan Dana']);
    }
}
