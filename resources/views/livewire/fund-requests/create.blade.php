<div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            {{-- Back --}}
            <a href="{{ route('fund-requests.index') }}" wire:navigate
                class="d-inline-flex align-items-center gap-2 text-muted small fw-600 mb-3 text-decoration-none"
                style="transition:color .15s" onmouseover="this.style.color='var(--sk-primary)'"
                onmouseout="this.style.color=''">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="sk-card">
                {{-- Header --}}
                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom:1px solid rgba(0,0,0,.07)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:36px;height:36px;background:var(--sk-primary-light)">
                        <i class="bi bi-send-fill" style="color:var(--sk-primary);font-size:.9rem"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0" style="font-size:.95rem">Ajukan Dana</h6>
                        <p class="text-muted mb-0" style="font-size:.75rem">Isi detail pengajuan dana</p>
                    </div>
                </div>

                {{-- Form --}}
                <form wire:submit="save">

                    {{-- Row 1: Jumlah & Bulan --}}
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-600 small mb-1">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text fw-700 bg-white" style="color:var(--sk-primary)">Rp</span>
                                <input type="number" wire:model="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="0" min="1" step="1">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small mb-1">Bulan <span class="text-danger">*</span></label>
                            <input type="month" wire:model="month"
                                class="form-control form-control-sm @error('month') is-invalid @enderror">
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Alasan --}}
                    <div class="mb-3">
                        <label class="form-label fw-600 small mb-1">Alasan Pengajuan <span class="text-danger">*</span></label>
                        <textarea wire:model="reason" rows="3"
                            class="form-control form-control-sm @error('reason') is-invalid @enderror"
                            placeholder="Contoh: Kebutuhan biaya sekolah, renovasi rumah, dll."></textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 justify-content-end pt-2" style="border-top:1px solid rgba(0,0,0,.06)">
                        <a href="{{ route('fund-requests.index') }}" wire:navigate
                            class="btn btn-sm btn-light fw-600" style="border-radius:.5rem">Batal</a>
                        <button type="submit" class="btn btn-sm fw-700 text-white"
                            style="background:var(--sk-primary-gradient);border-radius:.5rem;border:none;padding:.4rem 1.2rem">
                            <span wire:loading wire:target="save"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <i wire:loading.remove wire:target="save" class="bi bi-send-fill me-1"></i>
                            Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>