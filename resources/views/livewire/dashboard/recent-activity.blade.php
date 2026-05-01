{{-- resources/views/livewire/dashboard/recent-activity.blade.php --}}
<div class="mt-4 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold mb-0" style="font-size:1rem">
                @if ($isAdmin)
                    <i class="bi bi-activity me-2" style="color:var(--sk-primary)"></i>Aktivitas Terbaru
                @else
                    <i class="bi bi-person-lines-fill me-2" style="color:var(--sk-primary)"></i>Aktivitas Saya
                @endif
            </h5>
            <p class="mb-0 mt-1" style="font-size:.75rem;color:#adb5bd">
                @if ($isAdmin)
                    Semua transaksi dari seluruh pengguna
                @else
                    Riwayat transaksi & pengajuan dana kamu
                @endif
            </p>
        </div>

        {{-- ── Filter Bar ── --}}
        <div class="d-flex flex-wrap align-items-center gap-2">

            {{-- Search --}}
            <div class="input-group input-group-sm" style="width:200px">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted" style="font-size:.75rem"></i>
                </span>
                <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0 ps-0"
                    placeholder="Cari keterangan..." style="font-size:.8rem">
            </div>

            {{-- Tipe --}}
            <select wire:model.live="type" class="form-select form-select-sm" style="width:auto;font-size:.8rem">
                <option value="">Semua Tipe</option>
                <option value="expense">Pengeluaran</option>
                <option value="fund_request">Pengajuan Dana</option>
                @if ($isAdmin)
                    <option value="income">Pemasukan</option>
                @endif
            </select>

            {{-- Bulan --}}
            <input type="month" wire:model.live="month" class="form-control form-control-sm"
                style="width:auto;font-size:.8rem">

            {{-- Per Halaman --}}
            <select wire:model.live="perPage" class="form-select form-select-sm" style="width:auto;font-size:.8rem">
                <option value="10">10 / hal</option>
                <option value="25">25 / hal</option>
                <option value="50">50 / hal</option>
            </select>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr style="background:#f8f9fa">
                    <th class="ps-3 py-3 border-0"
                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                        Tanggal</th>
                    <th class="py-3 border-0"
                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                        Tipe</th>
                    @if ($isAdmin)
                        <th class="py-3 border-0"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                            Pengguna</th>
                    @endif
                    <th class="py-3 border-0"
                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                        Keterangan</th>
                    <th class="py-3 border-0"
                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                        Status</th>
                    <th class="pe-3 py-3 border-0 text-end"
                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                        Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    @php
                        $modelClass = match ($activity->type) {
                            'income' => \App\Models\Income::class,
                            'fund_request' => \App\Models\FundRequest::class,
                            default => \App\Models\Expense::class,
                        };

                        $typeConfig = $modelClass::typeConfig();
                        $statusBadge = $modelClass::statusBadge($activity->status);
                        $amountColor = $typeConfig['amount_color'];
                    @endphp
                    <tr wire:key="activity-{{ $activity->type }}-{{ $activity->id }}"
                        style="border-bottom:1px solid rgba(0,0,0,.04);transition:background .1s"
                        onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">

                        {{-- Tanggal --}}
                        <td class="ps-3 py-3" style="font-size:.82rem;color:#6c757d;white-space:nowrap">
                            <i class="bi bi-calendar3 me-1" style="font-size:.7rem"></i>
                            {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}
                        </td>

                        {{-- Tipe --}}
                        <td class="py-3">
                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                style="background:{{ $typeConfig['bg'] }};color:{{ $typeConfig['color'] }};font-size:.72rem;font-weight:600;white-space:nowrap">
                                <i class="bi {{ $typeConfig['icon'] }}"></i>
                                {{ $activity->type_label }}
                            </span>
                        </td>

                        {{-- Pengguna (admin only) --}}
                        @if ($isAdmin)
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sk-avatar" style="width:28px;height:28px;font-size:.7rem;flex-shrink:0">
                                        {{ strtoupper(substr($activity->user_name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:.82rem;font-weight:600">{{ $activity->user_name }}</span>
                                </div>
                            </td>
                        @endif

                        {{-- Keterangan --}}
                        <td class="py-3"
                            style="font-size:.82rem;color:var(--sk-text);max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $activity->label }}
                        </td>

                        {{-- Status --}}
                        <td class="py-3">
                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                style="background:{{ $statusBadge['bg'] }};color:{{ $statusBadge['color'] }};font-size:.72rem;font-weight:600">
                                <i class="bi {{ $statusBadge['icon'] }}"></i>
                                {{ $statusBadge['label'] }}
                            </span>
                        </td>

                        {{-- Jumlah --}}
                        <td class="pe-3 py-3 text-end" style="white-space:nowrap">
                            <span style="font-size:.9rem;font-weight:700;color:{{ $amountColor }}">
                                {{ $activity->amount_sign }}Rp {{ number_format($activity->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="text-center py-5" style="color:#adb5bd">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                            <span class="small">Tidak ada aktivitas ditemukan</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Footer: info + pagination ── --}}
    @if ($activities->hasPages())
        <div class="d-flex align-items-center justify-content-between px-1 pt-3 border-top flex-wrap gap-2"
            style="background:#fafafa;border-radius:0 0 1rem 1rem">
            <span style="font-size:.75rem;color:#adb5bd">
                Menampilkan {{ $activities->firstItem() }}–{{ $activities->lastItem() }}
                dari {{ $activities->total() }} aktivitas
            </span>
            {{ $activities->links() }}
        </div>
    @else
        <div class="pt-3 border-top" style="font-size:.75rem;color:#adb5bd">
            {{ $activities->total() }} aktivitas ditemukan
        </div>
    @endif
</div>
