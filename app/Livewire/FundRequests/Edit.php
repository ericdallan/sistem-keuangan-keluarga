<?php

namespace App\Livewire\FundRequests;

use App\Models\FundRequest;
use App\Services\FundRequestService;
use Livewire\Component;

class Edit extends Component
{
    public FundRequest $fundRequest;

    public string $amount = '';
    public string $reason = '';
    public string $month  = '';

    protected FundRequestService $service;

    public function boot(FundRequestService $service): void
    {
        $this->service = $service;
    }

    public function mount(FundRequest $fundRequest): void
    {
        $this->authorize('update', $fundRequest);
        $this->fundRequest = $fundRequest;
        $this->amount      = (string) $fundRequest->amount;
        $this->reason      = $fundRequest->reason;
        $this->month       = $fundRequest->month;
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
            'amount.required'   => 'Jumlah wajib diisi.',
            'amount.numeric'    => 'Jumlah harus berupa angka.',
            'amount.min'        => 'Jumlah minimal Rp 1.',
            'reason.required'   => 'Alasan wajib diisi.',
            'reason.max'        => 'Alasan maksimal 255 karakter.',
            'month.required'    => 'Bulan wajib diisi.',
            'month.date_format' => 'Format bulan tidak valid.',
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->fundRequest);
        $data = $this->validate();

        $this->service->update($this->fundRequest, $data);

        $this->dispatch('toast', message: 'Pengajuan dana berhasil diperbarui.', type: 'success');
        $this->redirectRoute('fund-requests.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.fund-requests.edit')
            ->layout('livewire.layout.app', ['title' => 'Edit Pengajuan Dana']);
    }
}
