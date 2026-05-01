<div>
    {{-- ── Filter Bar ── --}}
    <div class="sk-card mb-4">
        <div class="row g-2 align-items-end">

            {{-- Cari --}}
            <div class="col-12 col-md-3">
                <label class="form-label small fw-600 mb-1">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0 ps-0"
                        placeholder="Cari alasan...">
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
                <input type="month" wire:model.live="month" class="form-control form-control-sm">
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
                    <a href="{{ route('fund-requests.create') }}" wire:navigate
                        class="btn btn-sm fw-700 text-white w-100 d-flex align-items-center justify-content-center gap-1"
                        style="background:var(--sk-primary-gradient);border-radius:0.6rem;padding:.5rem 1rem;border:none;box-shadow:0 3px 10px rgba(25,135,84,.25);height:31px">
                        <i class="bi bi-plus-lg"></i> <span>Ajukan</span>
                    </a>
                @else
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
                        <th class="py-3 fw-700 text-muted">Alasan</th>
                        <th class="py-3 fw-700 text-muted">Jumlah</th>
                        <th class="py-3 fw-700 text-muted">Bulan</th>
                        <th class="py-3 fw-700 text-muted">Tanggal Ajuan</th>
                        <th class="py-3 fw-700 text-muted">Status</th>
                        <th class="py-3 fw-700 text-muted text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fundRequests as $fund)
                        @php $badge = $fund->status_badge; @endphp
                        <tr wire:key="fund-{{ $fund->id }}">
                            <td class="px-4 text-muted">{{ $fundRequests->firstItem() + $loop->index }}</td>

                            @if ($isAdmin)
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="sk-avatar" style="width:28px;height:28px;font-size:.75rem">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-600">{{ $fund->user->name }}</span>
                                    </div>
                                </td>
                            @endif

                            <td>
                                <span class="fw-500"
                                    style="max-width:220px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $fund->reason }}
                                </span>
                            </td>

                            <td class="fw-700" style="color:var(--sk-primary)">
                                Rp {{ number_format($fund->amount, 0, ',', '.') }}
                            </td>

                            <td class="text-muted">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $fund->month)->translatedFormat('F Y') }}
                            </td>

                            <td class="text-muted">
                                {{ $fund->created_at->format('d M Y') }}
                            </td>

                            <td>
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-600"
                                    style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:.75rem">
                                    <i class="bi {{ $badge['icon'] }}" style="font-size:.65rem"></i>
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <td class="px-4 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    @if ($isAdmin)
                                        @if ($fund->status === 'pending')
                                            <button wire:click="confirmAction({{ $fund->id }}, 'approve')"
                                                class="sk-icon-btn text-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button wire:click="confirmAction({{ $fund->id }}, 'reject')"
                                                class="sk-icon-btn text-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small px-2">—</span>
                                        @endif
                                    @else
                                        @if ($fund->status === 'pending')
                                            <a href="{{ route('fund-requests.edit', $fund) }}" wire:navigate
                                                class="sk-icon-btn" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button wire:click="confirmDelete({{ $fund->id }})"
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
                                <span class="small">Tidak ada data pengajuan dana</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($fundRequests->hasPages())
            <div class="px-4 py-3 border-top" style="background:#fafafa">
                {{ $fundRequests->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal: Konfirmasi Hapus ── --}}
    <div class="modal fade" id="modal-delete-fund" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                        style="width:52px;height:52px;background:#fff5f5">
                        <i class="bi bi-trash3-fill text-danger" style="font-size:1.4rem"></i>
                    </div>
                    <h6 class="fw-700 mb-1">Hapus Pengajuan?</h6>
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
    <div class="modal fade" id="modal-action-fund" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-body text-center p-4">
                    @if ($actionType === 'approve')
                        {{-- Icon --}}
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                            style="width:52px;height:52px;background:#d1e7dd">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:1.4rem"></i>
                        </div>

                        <h6 class="fw-700 mb-1">Setujui Pengajuan?</h6>
                        <p class="text-muted small mb-3">Status pengajuan akan diubah menjadi
                            <strong>Disetujui</strong>.
                        </p>

                        {{-- Info pencairan otomatis --}}
                        @if ($actionId)
                            @php
                                $fundForModal = $fundRequests->firstWhere('id', $actionId);
                            @endphp
                            @if ($fundForModal)
                                <div class="p-3 mb-3 text-start"
                                    style="background:#f0fdf4;border-radius:.75rem;border:1px solid #bbf7d0">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-arrow-down-circle-fill text-success"></i>
                                        <span class="fw-700 small">Otomatis masuk ke Pemasukan</span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Jumlah</span>
                                        <span class="fw-700" style="color:#198754">
                                            Rp {{ number_format($fundForModal->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Bulan</span>
                                        <span class="fw-600">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $fundForModal->month)->translatedFormat('F Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Keterangan</span>
                                        <span class="fw-600 text-end" style="max-width:140px">
                                            Pencairan Dana:
                                            {{ \Illuminate\Support\Str::limit($fundForModal->reason, 30) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endif

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
                        <h6 class="fw-700 mb-1">Tolak Pengajuan?</h6>
                        <p class="text-muted small mb-4">Status pengajuan akan diubah menjadi <strong>Ditolak</strong>.
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
</div>
