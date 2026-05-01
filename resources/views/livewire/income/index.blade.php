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
                        placeholder="Cari deskripsi...">
                </div>
            </div>

            {{-- Kategori --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-600 mb-1">Kategori</label>
                <select wire:model.live="category" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="salary">Gaji</option>
                    <option value="bonus">Bonus</option>
                    <option value="fund_request">Pengajuan Dana</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            {{-- Bulan --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-600 mb-1">Bulan</label>
                <select wire:model.live="month" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div class="col-6 col-md-1">
                <label class="form-label small fw-600 mb-1">Tahun</label>
                <select wire:model.live="year" class="form-select form-select-sm">
                    <option value="">Semua</option>
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

            {{-- Tombol Tambah --}}
            <div class="col-12 col-md-auto">
                <a href="{{ route('income.create') }}" wire:navigate
                    class="btn btn-sm fw-700 text-white w-100 d-flex align-items-center justify-content-center gap-1"
                    style="background:var(--sk-primary-gradient);border-radius:0.6rem;padding:.5rem 1rem;border:none;box-shadow:0 3px 10px rgba(25,135,84,.25);height:31px">
                    <i class="bi bi-plus-lg"></i> <span>Tambah</span>
                </a>
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
                        <th class="py-3 fw-700 text-muted">Pengguna</th>
                        <th class="py-3 fw-700 text-muted">Deskripsi</th>
                        <th class="py-3 fw-700 text-muted">Kategori</th>
                        <th class="py-3 fw-700 text-muted">Jumlah</th>
                        <th class="py-3 fw-700 text-muted">Tanggal</th>
                        <th class="py-3 fw-700 text-muted text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incomes as $income)
                        @php $badge = $income->category_badge; @endphp
                        <tr wire:key="income-{{ $income->uuid_incomes }}">
                            <td class="px-4 text-muted">{{ $incomes->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sk-avatar" style="width:28px;height:28px;font-size:.75rem">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-600">{{ $income->user->name }}</span>
                                </div>
                            </td>

                            <td>
                                <span class="fw-500"
                                    style="max-width:220px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $income->description }}
                                </span>
                            </td>

                            <td>
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-600"
                                    style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:.75rem">
                                    <i class="bi {{ $badge['icon'] }}" style="font-size:.65rem"></i>
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <td class="fw-700" style="color:#198754">
                                Rp {{ number_format($income->amount, 0, ',', '.') }}
                            </td>

                            <td class="text-muted">
                                {{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}
                            </td>

                            <td class="px-4 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    @if ($income->is_mutable)
                                        <a href="{{ route('income.edit', $income->uuid_incomes) }}" wire:navigate
                                            class="sk-icon-btn me-1" title="Edit">
                                            <i class="bi bi-pencil" style="color:var(--sk-primary)"></i>
                                        </a>
                                        <button wire:click="confirmDelete('{{ $income->uuid_incomes }}')"
                                            class="sk-icon-btn text-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @else
                                        <button class="sk-icon-btn me-1" disabled style="opacity:.35;cursor:not-allowed"
                                            title="Dari pengajuan dana">
                                            <i class="bi bi-pencil" style="color:var(--sk-primary)"></i>
                                        </button>
                                        <button class="sk-icon-btn" disabled style="opacity:.35;cursor:not-allowed"
                                            title="Dari pengajuan dana">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                                <span class="small">Tidak ada data pemasukan</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($incomes->hasPages())
            <div class="px-4 py-3 border-top" style="background:#fafafa">
                {{ $incomes->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal: Konfirmasi Hapus ── --}}
    <div class="modal fade" id="modal-delete-income" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow" style="border-radius:1rem">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                        style="width:52px;height:52px;background:#fff5f5">
                        <i class="bi bi-trash3-fill text-danger" style="font-size:1.4rem"></i>
                    </div>
                    <h6 class="fw-700 mb-1">Hapus Pemasukan?</h6>
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
</div>
