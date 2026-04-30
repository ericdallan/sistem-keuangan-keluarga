<div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">

            {{-- Back --}}
            <a href="{{ route('expenses.index') }}" wire:navigate
                class="d-inline-flex align-items-center gap-2 text-muted small fw-600 mb-4 text-decoration-none"
                style="transition:color .15s" onmouseover="this.style.color='var(--sk-primary)'"
                onmouseout="this.style.color=''">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>

            <div class="sk-card">
                {{-- Header --}}
                <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid rgba(0,0,0,.07)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:44px;height:44px;background:#fff3cd">
                        <i class="bi bi-pencil-fill" style="color:#856404;font-size:1.1rem"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0">Edit Pengeluaran</h6>
                        <p class="text-muted small mb-0">Perbarui detail pengeluaran (hanya status pending)</p>
                    </div>
                </div>

                {{-- Status Warning --}}
                @if ($expense->status !== 'pending')
                    <div class="alert d-flex align-items-center gap-2 mb-4"
                        style="background:#fff3cd;border:1px solid #ffc107;border-radius:.7rem;color:#664d03;font-size:.875rem">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                        Pengeluaran ini sudah
                        <strong>{{ $expense->status === 'approved' ? 'disetujui' : 'ditolak' }}</strong> dan tidak dapat
                        diubah.
                    </div>
                @endif

                {{-- Form --}}
                <form wire:submit="save" enctype="multipart/form-data">

                    {{-- Jumlah --}}
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-700 bg-white" style="color:var(--sk-primary)">Rp</span>
                            <input type="number" wire:model="amount"
                                class="form-control @error('amount') is-invalid @enderror" placeholder="0"
                                min="1" step="1" {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" wire:model="description"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Contoh: Beli ATK, Transport, dll."
                            {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" wire:model="date" class="form-control @error('date') is-invalid @enderror"
                            max="{{ now()->format('Y-m-d') }}" {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bukti existing --}}
                    @if ($expense->evidence_path)
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Bukti Saat Ini</label>
                            <div>
                                <a href="{{ asset('storage/' . $expense->evidence_path) }}" target="_blank"
                                    class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded text-decoration-none"
                                    style="background:var(--sk-primary-light);color:var(--sk-primary);font-size:.85rem;font-weight:600">
                                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Lihat Bukti
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Upload Bukti Baru --}}
                    <div class="mb-4">
                        <label class="form-label fw-600 small">
                            {{ $expense->evidence_path ? 'Ganti Bukti (Opsional)' : 'Bukti Pengeluaran' }}
                        </label>
                        <input type="file" wire:model="evidence"
                            class="form-control @error('evidence') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf"
                            {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
                        <div class="form-text">Format: JPG, PNG, PDF. Maks 2 MB.</div>
                        @error('evidence')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @if ($evidence)
                            <div class="mt-2 p-2 rounded d-inline-flex align-items-center gap-2"
                                style="background:var(--sk-primary-light);font-size:.8rem;color:var(--sk-primary)">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span class="fw-600">{{ $evidence->getClientOriginalName() }}</span>
                            </div>
                        @endif

                        <div wire:loading wire:target="evidence" class="mt-2">
                            <span class="spinner-border spinner-border-sm text-success me-1"></span>
                            <span class="small text-muted">Mengupload...</span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('expenses.index') }}" wire:navigate class="btn btn-light fw-600"
                            style="border-radius:.6rem">Batal</a>
                        @if ($expense->status === 'pending')
                            <button type="submit" class="btn fw-700 text-white"
                                style="background:var(--sk-primary-gradient);border-radius:.6rem;border:none;box-shadow:0 3px 10px rgba(25,135,84,.25);padding:.5rem 1.4rem">
                                <span wire:loading wire:target="save"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                <i wire:loading.remove wire:target="save" class="bi bi-floppy-fill me-1"></i>
                                Perbarui
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
