<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Services\ExpenseService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Expense $expense;

    public string $amount      = '';
    public string $description = '';
    public string $date        = '';
    public $evidence           = null;
    public ?string $previewEvidenceUrl = null;
    public ?string $previewEvidenceType = null;

    protected ExpenseService $service;

    public function boot(ExpenseService $service): void
    {
        $this->service = $service;
    }

    public function mount(Expense $expense): void
    {
        $this->authorize('update', $expense);
        $this->expense     = $expense;
        $this->amount      = (string) $expense->amount;
        $this->description = $expense->description;
        $this->date        = $expense->date->format('Y-m-d');
    }
    public function previewEvidence(): void
    {
        // Karena kita sudah punya $this->expense, tidak perlu query lagi
        if (!$this->expense->evidence_path) {
            $this->dispatch('toast', message: 'Bukti tidak ditemukan.', type: 'error');
            return;
        }

        $this->previewEvidenceUrl = asset('storage/' . $this->expense->evidence_path);

        $ext = strtolower(pathinfo($this->expense->evidence_path, PATHINFO_EXTENSION));
        $this->previewEvidenceType = in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';

        $this->dispatch('open-modal', modal: 'modal-preview-evidence');
    }

    public function closePreview(): void
    {
        $this->reset('previewEvidenceUrl', 'previewEvidenceType');
    }
    
    protected function rules(): array
    {
        return [
            'amount'      => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date', 'before_or_equal:today'],
            'evidence'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

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

    public function save(): void
    {
        $this->authorize('update', $this->expense);
        $data = $this->validate();

        $this->service->update($this->expense, $data, $this->evidence);

        $this->dispatch('toast', message: 'Pengeluaran berhasil diperbarui.', type: 'success');
        $this->redirectRoute('expenses.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.expenses.edit')
            ->layout('livewire.layout.app', ['title' => 'Edit Pengeluaran']);
    }
}
