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
                        style="width:36px;height:36px;background:#fff3cd">
                        <i class="bi bi-pencil-fill" style="color:#856404;font-size:.9rem"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0" style="font-size:.95rem">Edit Pengeluaran</h6>
                        <p class="text-muted mb-0" style="font-size:.75rem">Hanya status pending yang dapat diubah</p>
                    </div>
                </div>

                {{-- Status Warning --}}
                @if ($expense->status !== 'pending')
                    <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mb-3"
                        style="border-radius:.6rem;font-size:.8rem">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                        <span>Sudah <strong>{{ $expense->status === 'approved' ? 'disetujui' : 'ditolak' }}</strong>
                            &mdash; tidak dapat diubah.</span>
                    </div>
                @endif

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
                                    min="1" step="1"
                                    {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
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
                                max="{{ now()->format('Y-m-d') }}"
                                {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
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
                            placeholder="Contoh: Beli ATK, Transport, dll."
                            {{ $expense->status !== 'pending' ? 'disabled' : '' }}>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Row 3: Evidence --}}
                    <div class="row g-2 mb-3">
                        {{-- Bukti existing --}}
                        @if ($expense->evidence_path)
                            <div class="col-md-5">
                                <label class="form-label fw-600 small mb-1">Bukti Saat Ini</label>
                                <button type="button" wire:click="previewEvidence"
                                    class="btn btn-sm d-flex align-items-center gap-2 w-100 justify-content-center"
                                    style="background:var(--sk-primary-light); color:var(--sk-primary); border:none;">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                            </div>
                        @endif

                        {{-- Upload Bukti Baru --}}
                        <div class="{{ $expense->evidence_path ? 'col-md-7' : 'col-12' }}">
                            <label class="form-label fw-600 small mb-1">
                                {{ $expense->evidence_path ? 'Ganti (Opsional)' : 'Bukti Pengeluaran' }}
                            </label>

                            <input type="file" wire:model="evidence"
                                class="form-control form-control-sm @error('evidence') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.pdf" {{ $expense->status !== 'pending' ? 'disabled' : '' }}>

                            <div class="form-text" style="font-size:.7rem">JPG, PNG, PDF. Maks 2 MB.</div>

                            @error('evidence')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Preview Section --}}
                            @if ($evidence)
                                <div class="mt-3">
                                    {{-- 1. Label/Judul Selalu di Atas --}}
                                    <div class="mb-2">
                                        <label class="small fw-800 text-muted text-uppercase"
                                            style="font-size: .65rem; letter-spacing: .5px;">
                                            <i class="bi bi-eye me-1"></i> Preview Bukti Baru
                                        </label>
                                    </div>

                                    {{-- 2. Area Gambar Preview --}}
                                    @if (in_array($evidence->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                        <div class="position-relative d-inline-block mb-2">
                                            <img src="{{ $evidence->temporaryUrl() }}" class="img-thumbnail shadow-sm"
                                                style="max-height: 120px; border-radius: .5rem; border-color: #ddd;">
                                            <button type="button" wire:click="$set('evidence', null)"
                                                class="btn btn-sm btn-danger shadow-sm position-absolute top-0 end-0 m-1"
                                                style="line-height:1; border-radius:50%; font-size: .7rem; padding: 4px 7px;">
                                                &times;
                                            </button>
                                        </div>
                                    @elseif ($evidence->getClientOriginalExtension() === 'pdf')
                                        <button type="button" data-action="preview-pdf-upload"
                                            class="btn btn-sm d-flex align-items-center gap-2 mb-2"
                                            style="background:var(--sk-primary-light);color:var(--sk-primary);border:none;border-radius:.5rem">
                                            <i class="bi bi-eye"></i> Lihat Preview PDF
                                        </button>
                                        <button type="button" wire:click="$set('evidence', null)"
                                            class="btn btn-sm btn-outline-danger mb-2"
                                            style="border-radius:.4rem;font-size:.75rem">
                                            <i class="bi bi-trash3 me-1"></i> Hapus
                                        </button>
                                    @endif
                                    {{-- 3. Info Nama File --}}
                                    <div class="d-flex align-items-center gap-2 p-2 rounded"
                                        style="background:var(--sk-primary-light);font-size:.8rem;color:var(--sk-primary)">
                                        <i class="bi bi-file-earmark-check-fill"></i>
                                        <span
                                            class="fw-600 text-truncate">{{ $evidence->getClientOriginalName() }}</span>
                                    </div>
                                </div>
                            @endif

                            <div wire:loading wire:target="evidence" class="mt-2">
                                <span class="spinner-border spinner-border-sm text-success me-1"></span>
                                <span class="small text-muted">Mengupload...</span>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 justify-content-end pt-2" style="border-top:1px solid rgba(0,0,0,.06)">
                        <a href="{{ route('expenses.index') }}" wire:navigate class="btn btn-sm btn-light fw-600"
                            style="border-radius:.5rem">Batal</a>
                        @if ($expense->status === 'pending')
                            <button type="submit" class="btn btn-sm fw-700 text-white"
                                style="background:var(--sk-primary-gradient);border-radius:.5rem;border:none;padding:.4rem 1.2rem">
                                <span wire:loading wire:target="save"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                <i wire:loading.remove wire:target="save" class="bi bi-floppy-fill me-1"></i>
                                Simpan
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
    {{-- ── Modal: Preview Evidence ── --}}
    <div class="modal fade" id="modal-preview-evidence" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-700 mb-0"><i class="bi bi-eye me-2"></i>Preview Bukti</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="closePreview"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    @if ($previewEvidenceUrl)
                        @if ($previewEvidenceType === 'image')
                            <img src="{{ $previewEvidenceUrl }}" alt="Bukti" class="img-fluid rounded shadow-sm"
                                style="max-height:70vh; object-fit:contain;">
                        @else
                            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                                <iframe src="{{ $previewEvidenceUrl }}" style="border:none;"></iframe>
                            </div>
                            <a href="{{ $previewEvidenceUrl }}" target="_blank"
                                class="btn btn-sm btn-outline-primary mt-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                            </a>
                        @endif
                    @else
                        <div class="py-5 text-muted"><span>Memuat...</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Preview PDF Upload --}}
    <div class="modal fade" id="modal-pdf-upload-preview" tabindex="-1" aria-modal="true" role="dialog"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-700 mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Preview PDF</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div style="height:70vh">
                        <iframe id="pdf-upload-frame" src="" width="100%" height="100%"
                            style="border:none;border-radius:.5rem"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[data-action="preview-pdf-upload"]')) return;

                const input = document.querySelector('input[type="file"]');
                if (!input || !input.files || !input.files[0]) return;

                const blobUrl = URL.createObjectURL(input.files[0]);
                const frame = document.getElementById('pdf-upload-frame');
                if (frame) frame.src = blobUrl;

                const modalEl = document.getElementById('modal-pdf-upload-preview');
                if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });

            // Reset iframe saat modal ditutup
            document.getElementById('modal-pdf-upload-preview')
                ?.addEventListener('hidden.bs.modal', function() {
                    const frame = document.getElementById('pdf-upload-frame');
                    if (frame) frame.src = '';
                });
        </script>
    @endscript
</div>
