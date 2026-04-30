<div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            {{-- Back --}}
            <a href="{{ route('expenses.index') }}" wire:navigate
                class="d-inline-flex align-items-center gap-2 text-muted small fw-600 mb-3 text-decoration-none"
                style="transition:color .15s" onmouseover="this.style.color='var(--sk-primary)'"
                onmouseout="this.style.color=''">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="sk-card">
                {{-- Compact Header --}}
                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom:1px solid rgba(0,0,0,.07)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:36px;height:36px;background:var(--sk-primary-light)">
                        <i class="bi bi-plus-circle-fill" style="color:var(--sk-primary);font-size:.9rem"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0" style="font-size:.95rem">Tambah Pengeluaran</h6>
                        <p class="text-muted mb-0" style="font-size:.75rem">Isi detail pengeluaran untuk diajukan</p>
                    </div>
                </div>

                {{-- Form --}}
                <form wire:submit="save" enctype="multipart/form-data">

                    {{-- Row 1: Jumlah & Tanggal --}}
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-600 small mb-1">Jumlah <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text fw-700 bg-white" style="color:var(--sk-primary)">Rp</span>
                                <input type="number" wire:model="amount"
                                    class="form-control @error('amount') is-invalid @enderror" placeholder="0"
                                    min="1" step="1">
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
                                max="{{ now()->format('Y-m-d') }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Deskripsi --}}
                    <div class="mb-2">
                        <label class="form-label fw-600 small mb-1">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" wire:model="description"
                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                            placeholder="Contoh: Beli ATK, Transport, dll.">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Row 3: Bukti --}}
                    <div class="mb-3">
                        <label class="form-label fw-600 small mb-1">Bukti Pengeluaran</label>
                        <input type="file" wire:model="evidence"
                            class="form-control form-control-sm @error('evidence') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text" style="font-size:.7rem">Format: JPG, PNG, PDF. Maks 2 MB.</div>
                        @error('evidence')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Preview Section --}}
                        @if ($evidence)
                            <div class="mt-3">
                                {{-- Label Preview --}}
                                <div class="mb-2">
                                    <label class="small fw-800 text-muted text-uppercase"
                                        style="font-size: .65rem; letter-spacing: .5px;">
                                        <i class="bi bi-eye me-1"></i> Preview Bukti
                                    </label>
                                </div>

                                {{-- Area Gambar --}}
                                @if (in_array($evidence->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                    <div class="position-relative d-inline-block mb-2">
                                        <img src="{{ $evidence->temporaryUrl() }}" class="img-thumbnail shadow-sm"
                                            style="max-height: 150px; border-radius: .5rem; border-color: #ddd;">

                                        {{-- Tombol Hapus --}}
                                        <button type="button" wire:click="$set('evidence', null)"
                                            class="btn btn-sm btn-danger shadow-sm position-absolute top-0 end-0 m-1"
                                            style="line-height:1; border-radius:50%; font-size: .7rem; padding: 4px 7px;">
                                            &times;
                                        </button>
                                    </div>
                                @endif

                                {{-- Nama File --}}
                                <div class="d-flex align-items-center gap-2 p-2 rounded"
                                    style="background:var(--sk-primary-light);font-size:.8rem;color:var(--sk-primary)">
                                    <i class="bi bi-file-earmark-check-fill"></i>
                                    <span class="fw-600 text-truncate">{{ $evidence->getClientOriginalName() }}</span>
                                </div>
                            </div>
                        @endif

                        <div wire:loading wire:target="evidence" class="mt-2">
                            <span class="spinner-border spinner-border-sm text-success me-1"></span>
                            <span class="small text-muted">Mengupload...</span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 justify-content-end pt-2" style="border-top:1px solid rgba(0,0,0,.06)">
                        <a href="{{ route('expenses.index') }}" wire:navigate class="btn btn-sm btn-light fw-600"
                            style="border-radius:.5rem">Batal</a>
                        <button type="submit" class="btn btn-sm fw-700 text-white"
                            style="background:var(--sk-primary-gradient);border-radius:.5rem;border:none;padding:.4rem 1.2rem">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            <i wire:loading.remove wire:target="save" class="bi bi-send-fill me-1"></i>
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
