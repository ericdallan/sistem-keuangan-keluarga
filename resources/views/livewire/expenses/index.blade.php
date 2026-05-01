<div @if (!$isAdmin) wire:poll.15s @endif>
    {{-- ── Filter Bar ── --}}
    <div class="sk-card mb-4">
        <div class="row g-2 align-items-end">
            {{-- Cari --}}
            <div class="col-12 col-md-3">
                <label class="form-label small fw-600 mb-1">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0 ps-0"
                        placeholder="Deskripsi...">
                </div>
            </div>

            {{-- Status --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-600 mb-1">Status</label>
                <select wire:model.live="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            {{-- Bulan --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-600 mb-1">Bulan</label>
                <select wire:model.live="month" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div class="col-6 col-md-1">
                <label class="form-label small fw-600 mb-1">Tahun</label>
                <select wire:model.live="year" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach (range(now()->year, now()->year - 4) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Per Halaman --}}
            <div class="col-6 col-md-1">
                <label class="form-label small fw-600 mb-1">Show</label>
                <select wire:model.live="perPage" class="form-select form-select-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            {{-- Tombol Tambah (User Only) --}}
            <div class="col-12 col-md-auto">
                @if (!$isAdmin)
                    <a href="{{ route('expenses.create') }}" wire:navigate
                        class="btn btn-sm fw-700 text-white w-100 d-flex align-items-center justify-content-center gap-1"
                        style="background:var(--sk-primary-gradient);border-radius:0.6rem;padding:.5rem 1rem;border:none;box-shadow:0 3px 10px rgba(25,135,84,.25);height:31px">
                        <i class="bi bi-plus-lg"></i> <span>Tambah</span>
                    </a>
                @else
                    {{-- Placeholder untuk admin agar layout tetap seimbang --}}
                    <div class="d-none d-md-block" style="height:31px"></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="sk-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
                <thead style="background:#f8f9fa;border-bottom:2px solid rgba(0,0,0,.06)">
                    <tr>
                        <th class="px-4 py-3 fw-700 text-muted" style="width:50px">#</th>
                        @if ($isAdmin)
                            <th class="py-3 fw-700 text-muted">Pengguna</th>
                        @endif
                        <th class="py-3 fw-700 text-muted">Deskripsi</th>
                        <th class="py-3 fw-700 text-muted">Jumlah</th>
                        <th class="py-3 fw-700 text-muted">Tanggal</th>
                        <th class="py-3 fw-700 text-muted">Bukti</th>
                        <th class="py-3 fw-700 text-muted">Status</th>
                        <th class="py-3 fw-700 text-muted text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr wire:key="expense-{{ $expense->uuid_expenses }}">
                            <td class="px-4 text-muted">{{ $expenses->firstItem() + $loop->index }}</td>
                            @if ($isAdmin)
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="sk-avatar" style="width:28px;height:28px;font-size:.75rem">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-600">{{ $expense->user->name }}</span>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <span class="fw-500"
                                    style="max-width:200px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $expense->description }}
                                </span>
                            </td>
                            <td class="fw-700" style="color:var(--sk-primary)">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-muted">{{ $expense->date->format('d M Y') }}</td>
                            <td>
                                @if ($expense->evidence_path)
                                    <button wire:click="previewEvidence('{{ $expense->uuid_expenses }}')"
                                        class="btn btn-sm btn-outline-secondary py-0 px-2"
                                        style="border-radius:.4rem;font-size:.75rem">
                                        <i class="bi bi-paperclip me-1"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = $expense->status_badge;
                                @endphp
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-600"
                                    style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:.75rem">
                                    <i class="bi {{ $badge['icon'] }}" style="font-size:.65rem"></i>
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1">

                                    @if ($isAdmin)
                                        {{-- Admin: Approve & Reject (hanya jika pending) --}}
                                        @if ($expense->status === 'pending')
                                            <button
                                                wire:click="confirmAction('{{ $expense->uuid_expenses }}', 'approve')"
                                                class="sk-icon-btn text-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button
                                                wire:click="confirmAction('{{ $expense->uuid_expenses }}', 'reject')"
                                                class="sk-icon-btn text-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small px-2">—</span>
                                        @endif
                                    @else
                                        {{-- User: Edit & Delete (hanya jika pending) --}}
                                        @if ($expense->status === 'pending')
                                            <a href="{{ route('expenses.edit', $expense) }}" wire:navigate
                                                class="sk-icon-btn" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button wire:click="confirmDelete('{{ $expense->uuid_expenses }}')"
                                                class="sk-icon-btn text-danger" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small px-2">—</span>
                                        @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                                <span class="small">Tidak ada data pengeluaran</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($expenses->hasPages())
            <div class="px-4 py-3 border-top" style="background:#fafafa">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal: Konfirmasi Hapus ── --}}
    <div class="modal fade" id="modal-delete-expense" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                        style="width:52px;height:52px;background:#fff5f5">
                        <i class="bi bi-trash3-fill text-danger" style="font-size:1.4rem"></i>
                    </div>
                    <h6 class="fw-700 mb-1">Hapus Pengeluaran?</h6>
                    <p class="text-muted small mb-4">
                        "<strong>{{ $deleteDescription }}</strong>" akan dihapus secara permanen.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light fw-600 w-50"
                            data-bs-dismiss="modal">Batal</button>
                        <button wire:click="destroy" class="btn btn-danger fw-600 w-50">
                            <span wire:loading wire:target="destroy"
                                class="spinner-border spinner-border-sm me-1"></span>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal: Konfirmasi Approve/Reject ── --}}
    <div class="modal fade" id="modal-action-expense" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-body text-center p-4">
                    @if ($actionType === 'approve')
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                            style="width:52px;height:52px;background:#d1e7dd">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:1.4rem"></i>
                        </div>
                        <h6 class="fw-700 mb-1">Setujui Pengeluaran?</h6>
                        <p class="text-muted small mb-4">Status pengeluaran ini akan diubah menjadi
                            <strong>Disetujui</strong>.
                        </p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light fw-600 w-50"
                                data-bs-dismiss="modal">Batal</button>
                            <button wire:click="executeAction" class="btn btn-success fw-600 w-50">
                                <span wire:loading wire:target="executeAction"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                Setujui
                            </button>
                        </div>
                    @else
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                            style="width:52px;height:52px;background:#f8d7da">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size:1.4rem"></i>
                        </div>
                        <h6 class="fw-700 mb-1">Tolak Pengeluaran?</h6>
                        <p class="text-muted small mb-4">Status pengeluaran ini akan diubah menjadi
                            <strong>Ditolak</strong>.
                        </p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light fw-600 w-50"
                                data-bs-dismiss="modal">Batal</button>
                            <button wire:click="executeAction" class="btn btn-danger fw-600 w-50">
                                <span wire:loading wire:target="executeAction"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                Tolak
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal: Preview Evidence ── --}}
    <div class="modal fade" id="modal-preview-evidence" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-700 mb-0">
                        <i class="bi bi-eye me-2"></i>Preview Bukti
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="closePreview"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4 text-center">

                    @if ($previewEvidenceUrl)

                        {{-- Image Preview --}}
                        @if ($previewEvidenceType === 'image')
                            <img src="{{ $previewEvidenceUrl }}" alt="Bukti Pengeluaran"
                                class="img-fluid rounded shadow-sm" style="max-height:70vh; object-fit:contain;">

                            {{-- PDF Preview --}}
                        @else
                            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                                <iframe src="{{ $previewEvidenceUrl }}" style="border:none;"></iframe>
                            </div>
                            <a href="{{ $previewEvidenceUrl }}" target="_blank"
                                class="btn btn-sm btn-outline-primary mt-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Buka di Tab Baru
                            </a>
                        @endif
                    @else
                        <div class="py-5 text-muted">
                            <i class="bi bi-image d-block mb-2" style="font-size:2.5rem;opacity:.3"></i>
                            <span class="small">Memuat...</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
