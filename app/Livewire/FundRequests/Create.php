<?php

namespace App\Livewire\FundRequests;

use App\Services\FundRequestService;
use Livewire\Component;

class Create extends Component
{
    public string $amount = '';
    public string $reason = '';
    public string $month  = '';

    protected FundRequestService $service;

    public function boot(FundRequestService $service): void
    {
        $this->service = $service;
    }

    public function mount(): void
    {
        // Default bulan ke bulan ini
        $this->month = now()->format('Y-m');
    }

    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
            'month'  => ['required', 'date_format:Y-m'],
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric'  => 'Jumlah harus berupa angka.',
            'amount.min'      => 'Jumlah minimal Rp 1.',
            'reason.required' => 'Alasan wajib diisi.',
            'reason.max'      => 'Alasan maksimal 255 karakter.',
            'month.required'  => 'Bulan wajib diisi.',
            'month.date_format' => 'Format bulan tidak valid.',
        ];
    }

    public function save(): void
    {
        $this->authorize('create', \App\Models\FundRequest::class);
        $data = $this->validate();

        $this->service->store($data);

        $this->dispatch('toast', message: 'Pengajuan dana berhasil dikirim.', type: 'success');
        $this->redirectRoute('fund-requests.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.fund-requests.create')
            ->layout('livewire.layout.app', ['title' => 'Tambah Pengajuan Dana']);
    }
}
