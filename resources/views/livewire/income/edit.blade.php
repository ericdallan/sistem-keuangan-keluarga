<div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            {{-- Back --}}
            <a href="{{ route('income.index') }}" wire:navigate
                class="d-inline-flex align-items-center gap-2 text-muted small fw-600 mb-3 text-decoration-none"
                style="transition:color .15s" onmouseover="this.style.color='var(--sk-primary)'"
                onmouseout="this.style.color=''">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="sk-card">
                {{-- Compact Header --}}
                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom:1px solid rgba(0,0,0,.07)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:36px;height:36px;background:#fff3cd">
                        <i class="bi bi-pencil-fill" style="color:#856404;font-size:.9rem"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0" style="font-size:.95rem">Edit Pemasukan</h6>
                        <p class="text-muted mb-0" style="font-size:.75rem">Perbarui detail pemasukan yang tercatat</p>
                    </div>
                </div>

                {{-- Warning: dari fund_request --}}
                @if (!$income->is_mutable)
                    <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mb-3"
                        style="border-radius:.6rem;font-size:.8rem">
                        <i class="bi bi-lock-fill flex-shrink-0"></i>
                        <span>Pemasukan ini berasal dari <strong>pengajuan dana</strong> dan tidak dapat diubah.</span>
                    </div>
                @endif

                {{-- Form --}}
                <form wire:submit="update">

                    {{-- Row 1: Jumlah & Tanggal --}}
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-600 small mb-1">Jumlah <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text fw-700 bg-white" style="color:var(--sk-primary)">Rp</span>
                                <input type="number" wire:model="amount"
                                    class="form-control @error('amount') is-invalid @enderror" placeholder="0"
                                    min="1" step="1" {{ !$income->is_mutable ? 'disabled' : '' }}>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small mb-1">Tanggal <span
                                    class="text-danger">*</span></label>
                            <input type="date" wire:model="date"
                                class="form-control form-control-sm @error('date') is-invalid @enderror"
                                max="{{ now()->format('Y-m-d') }}" {{ !$income->is_mutable ? 'disabled' : '' }}>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Deskripsi & Kategori --}}
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-600 small mb-1">Deskripsi <span
                                    class="text-danger">*</span></label>
                            <input type="text" wire:model="description"
                                class="form-control form-control-sm @error('description') is-invalid @enderror"
                                placeholder="Contoh: Gaji bulan Mei, Bonus tahunan, dll."
                                {{ !$income->is_mutable ? 'disabled' : '' }}>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 small mb-1">Kategori <span
                                    class="text-danger">*</span></label>
                            <select wire:model="category"
                                class="form-select form-select-sm @error('category') is-invalid @enderror"
                                {{ !$income->is_mutable ? 'disabled' : '' }}>
                                <option value="">Pilih...</option>
                                <option value="salary">Gaji</option>
                                <option value="bonus">Bonus</option>
                                <option value="fund_request" disabled style="color:#adb5bd">
                                    Pengajuan Dana (otomatis)
                                </option>
                                <option value="other">Lainnya</option>
                            </select>
                            @if (!$income->is_mutable)
                                <div class="form-text" style="font-size:.7rem;color:#856404">
                                    <i class="bi bi-lock-fill me-1"></i>Dikunci karena berasal dari pengajuan dana.
                                </div>
                            @endif
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 justify-content-end pt-2" style="border-top:1px solid rgba(0,0,0,.06)">
                        <a href="{{ route('income.index') }}" wire:navigate class="btn btn-sm btn-light fw-600"
                            style="border-radius:.5rem">Batal</a>
                        @if ($income->is_mutable)
                            <button type="submit" class="btn btn-sm fw-700 text-white"
                                style="background:var(--sk-primary-gradient);border-radius:.5rem;border:none;padding:.4rem 1.2rem">
                                <span wire:loading wire:target="update"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                <i wire:loading.remove wire:target="update" class="bi bi-floppy-fill me-1"></i>
                                Perbarui
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
