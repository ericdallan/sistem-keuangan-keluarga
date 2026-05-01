{{-- resources/views/livewire/reports/index.blade.php --}}
<div class="py-2">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="px-4 py-3 text-white"
                    style="background:var(--sk-primary-gradient);border-radius:1rem;box-shadow:0 4px 20px rgba(25,135,84,.25)">
                    <h5 class="fw-bold mb-0">Laporan Keuangan</h5>
                    <p class="mb-0 opacity-75" style="font-size:.8rem">
                        {{ $report['month_label'] }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div>
                            <div
                                style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                                Dari Bulan
                            </div>
                            <input type="month" wire:model="startMonth" wire:change="$refresh"
                                class="form-control form-control-sm" style="width:180px;font-size:.85rem">
                        </div>

                        <div>
                            <div
                                style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                                Sampai Bulan
                            </div>
                            <input type="month" wire:model="endMonth" wire:change="$refresh"
                                class="form-control form-control-sm" style="width:180px;font-size:.85rem">
                        </div>

                        @if ($isAdmin)
                            <div>
                                <div
                                    style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                                    Pengguna
                                </div>
                                <select wire:model="selectedUser" wire:change="$refresh"
                                    class="form-select form-select-sm" style="width:180px;font-size:.85rem">
                                    <option value="">Semua Pengguna</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            @php
                $cards = [
                    [
                        'icon' => 'bi-wallet2',
                        'bg' => 'var(--sk-primary-light)',
                        'color' => 'var(--sk-primary)',
                        'label' => 'Saldo Akhir',
                        'value' => $report['summary']['balance'],
                    ],
                    [
                        'icon' => 'bi-arrow-down-circle',
                        'bg' => '#d1e7dd',
                        'color' => '#198754',
                        'label' => 'Pemasukan ' . $report['month_label'],
                        'value' => $report['summary']['total_income'],
                    ],
                    [
                        'icon' => 'bi-arrow-up-circle',
                        'bg' => '#f8d7da',
                        'color' => '#dc3545',
                        'label' => 'Pengeluaran ' . $report['month_label'],
                        'value' => $report['summary']['total_expense'],
                    ],
                    [
                        'icon' => 'bi-hourglass-split',
                        'bg' => '#fff3cd',
                        'color' => '#856404',
                        'label' => 'Pending ' . $report['month_label'],
                        'value' => $report['summary']['total_expense_pending'],
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-3 col-sm-6">
                    <div class="d-flex align-items-center gap-3 px-4 py-3 h-100"
                        style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                            style="width:44px;height:44px;background:{{ $card['bg'] }};color:{{ $card['color'] }};font-size:1.2rem">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <div>
                            <div
                                style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                                {{ $card['label'] }}
                            </div>
                            <div class="fw-bold" style="font-size:1.1rem;color:{{ $card['color'] }}">
                                Rp {{ number_format($card['value'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Top Users Section - Hanya muncul jika admin & tidak filter user & ada data --}}
        @if ($report['show_top_users'] && !empty($report['top_users']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-4"
                        style="background:#fff; border-radius:1rem; box-shadow:0 2px 12px rgba(0,0,0,.06)">

                        <h5 class="fw-bold mb-4" style="font-size:1rem">
                            <i class="bi bi-trophy me-2" style="color:var(--sk-primary)"></i>
                            Pengguna Paling Aktif
                        </h5>

                        <div class="row g-3">
                            @foreach ($report['top_users'] as $index => $topUser)
                                @php
                                    $isTop = $index === 0;
                                @endphp

                                <div class="col-md-4 col-sm-6">
                                    <div class="p-3"
                                        style="background:{{ $isTop ? 'var(--sk-primary-light)' : '#f8f9fa' }};
                                        border-radius:.75rem;
                                        border:1px solid {{ $isTop ? 'var(--sk-primary-light)' : 'rgba(0,0,0,.06)' }}">

                                        <!-- Baris 1: Icon + Nama + Total -->
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Rank Icon -->
                                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                                style="width:42px; height:42px;
                                                background:{{ $isTop ? 'var(--sk-primary)' : '#e2e3e5' }};
                                                color:{{ $isTop ? '#fff' : '#6c757d' }};
                                                font-size:1.05rem;">
                                                {{ $index + 1 }}
                                            </div>

                                            <!-- Nama User -->
                                            <div class="flex-grow-1 min-width-0">
                                                <div class="fw-semibold" style="font-size:.95rem; color:var(--sk-text)">
                                                    {{ $topUser['user_name'] }}
                                                </div>
                                            </div>

                                            <!-- Total Amount -->
                                            <div class="text-end flex-shrink-0" style="min-width: 90px;">
                                                <div class="fw-bold" style="font-size:1.05rem; color:var(--sk-primary)">
                                                    Rp {{ number_format($topUser['total_amount'], 0, ',', '.') }}
                                                </div>
                                                <div style="font-size:.75rem; color:#adb5bd;">Total</div>
                                            </div>
                                        </div>

                                        <!-- Baris 2: Pengeluaran (full width) -->
                                        <div class="mt-3 pt-3 border-top"
                                            style="border-color: rgba(0,0,0,.06) !important;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-arrow-up-circle"
                                                    style="color:#dc3545; font-size:1.1rem"></i>
                                                <span style="color:#6c757d; font-size:.85rem">
                                                    {{ number_format($topUser['expense_count']) }} pengeluaran
                                                </span>
                                                <span class="ms-auto fw-medium" style="color:#dc3545; font-size:.9rem">
                                                    Rp {{ number_format($topUser['expense_amount'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Baris 3: Pengajuan (full width) -->
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-cash-coin" style="color:#0dcaf0; font-size:1.1rem"></i>
                                                <span style="color:#6c757d; font-size:.85rem">
                                                    {{ number_format($topUser['fund_count']) }} pengajuan
                                                </span>
                                                <span class="ms-auto fw-medium" style="color:#0dcaf0; font-size:.9rem">
                                                    Rp {{ number_format($topUser['fund_amount'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table 1: Detail Pemasukan - HANYA ADMIN --}}
        @if ($report['show_income_detail'])
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-4"
                        style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-bold mb-0" style="font-size:1rem">
                                <i class="bi bi-arrow-down-circle me-2" style="color:#198754"></i>Detail Pemasukan
                            </h5>
                            <span class="rounded-pill px-3 py-1 fw-semibold"
                                style="font-size:.7rem;background:#d1e7dd;color:#198754">
                                {{ $incomes->total() }} transaksi
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr style="background:#f8f9fa">
                                        <th class="ps-3 py-3 border-0"
                                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                            Tanggal</th>
                                        <th class="py-3 border-0"
                                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                            Pengguna</th>
                                        <th class="py-3 border-0"
                                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                            Keterangan</th>
                                        <th class="pe-3 py-3 border-0 text-end"
                                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($incomes as $income)
                                        <tr style="border-bottom:1px solid rgba(0,0,0,.04)">
                                            <td class="ps-3 py-3"
                                                style="font-size:.82rem;color:#6c757d;white-space:nowrap">
                                                {{ $income->date->format('d M Y') }}
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="sk-avatar"
                                                        style="width:28px;height:28px;font-size:.7rem">
                                                        {{ strtoupper(substr($income->user->name ?? '-', 0, 1)) }}
                                                    </div>
                                                    <span
                                                        style="font-size:.82rem;font-weight:600">{{ $income->user->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3" style="font-size:.82rem;color:var(--sk-text)">
                                                {{ $income->description }}
                                            </td>
                                            <td class="pe-3 py-3 text-end fw-bold"
                                                style="font-size:.9rem;color:#198754;white-space:nowrap">
                                                +Rp {{ number_format($income->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5" style="color:#adb5bd">
                                                <i class="bi bi-inbox d-block mb-2"
                                                    style="font-size:2rem;opacity:.4"></i>
                                                <span class="small">Tidak ada pemasukan</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($incomes->hasPages())
                            <div class="d-flex align-items-center justify-content-between px-1 pt-3 border-top flex-wrap gap-2"
                                style="background:#fafafa;border-radius:0 0 1rem 1rem">
                                <span style="font-size:.75rem;color:#adb5bd">
                                    Menampilkan {{ $incomes->firstItem() }}–{{ $incomes->lastItem() }} dari
                                    {{ $incomes->total() }}
                                </span>
                                {{ $incomes->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Table 2: Pengeluaran --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="font-size:1rem">
                            <i class="bi bi-arrow-up-circle me-2" style="color:#dc3545"></i>Detail Pengeluaran
                        </h5>
                        <span class="rounded-pill px-3 py-1 fw-semibold"
                            style="font-size:.7rem;background:#f8d7da;color:#dc3545">
                            {{ $expenses->total() }} transaksi
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr style="background:#f8f9fa">
                                    <th class="ps-3 py-3 border-0"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                        Tanggal</th>
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
                                @forelse ($expenses as $expense)
                                    @php $badge = \App\Models\Expense::statusBadge($expense->status); @endphp
                                    <tr style="border-bottom:1px solid rgba(0,0,0,.04)">
                                        <td class="ps-3 py-3"
                                            style="font-size:.82rem;color:#6c757d;white-space:nowrap">
                                            {{ $expense->date->format('d M Y') }}
                                        </td>
                                        @if ($isAdmin)
                                            <td class="py-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="sk-avatar"
                                                        style="width:28px;height:28px;font-size:.7rem">
                                                        {{ strtoupper(substr($expense->user->name ?? '-', 0, 1)) }}
                                                    </div>
                                                    <span
                                                        style="font-size:.82rem;font-weight:600">{{ $expense->user->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="py-3" style="font-size:.82rem;color:var(--sk-text)">
                                            {{ $expense->description }}
                                        </td>
                                        <td class="py-3">
                                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                                style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:.72rem;font-weight:600">
                                                <i class="bi {{ $badge['icon'] }}"></i>{{ $badge['label'] }}
                                            </span>
                                        </td>
                                        <td class="pe-3 py-3 text-end fw-bold"
                                            style="font-size:.9rem;color:#dc3545;white-space:nowrap">
                                            -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-5"
                                            style="color:#adb5bd">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                                            <span class="small">Tidak ada pengeluaran</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($expenses->hasPages())
                        <div class="d-flex align-items-center justify-content-between px-1 pt-3 border-top flex-wrap gap-2"
                            style="background:#fafafa;border-radius:0 0 1rem 1rem">
                            <span style="font-size:.75rem;color:#adb5bd">
                                Menampilkan {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} dari
                                {{ $expenses->total() }}
                            </span>
                            {{ $expenses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table 3: Pengajuan Dana --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="font-size:1rem">
                            <i class="bi bi-cash-coin me-2" style="color:#0dcaf0"></i>Detail Pengajuan Dana
                        </h5>
                        <span class="rounded-pill px-3 py-1 fw-semibold"
                            style="font-size:.7rem;background:#cff4fc;color:#055160">
                            {{ $fundRequests->total() }} transaksi
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr style="background:#f8f9fa">
                                    <th class="ps-3 py-3 border-0"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                        Tanggal</th>
                                    @if ($isAdmin)
                                        <th class="py-3 border-0"
                                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                            Pengguna</th>
                                    @endif
                                    <th class="py-3 border-0"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                        Alasan</th>
                                    <th class="py-3 border-0"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                        Status</th>
                                    <th class="pe-3 py-3 border-0 text-end"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fundRequests as $fund)
                                    @php $badge = \App\Models\FundRequest::statusBadge($fund->status); @endphp
                                    <tr style="border-bottom:1px solid rgba(0,0,0,.04)">
                                        <td class="ps-3 py-3"
                                            style="font-size:.82rem;color:#6c757d;white-space:nowrap">
                                            {{ $fund->created_at->format('d M Y') }}
                                        </td>
                                        @if ($isAdmin)
                                            <td class="py-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="sk-avatar"
                                                        style="width:28px;height:28px;font-size:.7rem">
                                                        {{ strtoupper(substr($fund->user->name ?? '-', 0, 1)) }}
                                                    </div>
                                                    <span
                                                        style="font-size:.82rem;font-weight:600">{{ $fund->user->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="py-3" style="font-size:.82rem;color:var(--sk-text)">
                                            {{ $fund->reason }}
                                        </td>
                                        <td class="py-3">
                                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                                style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:.72rem;font-weight:600">
                                                <i class="bi {{ $badge['icon'] }}"></i>{{ $badge['label'] }}
                                            </span>
                                        </td>
                                        <td class="pe-3 py-3 text-end fw-bold"
                                            style="font-size:.9rem;color:#0dcaf0;white-space:nowrap">
                                            +Rp {{ number_format($fund->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-5"
                                            style="color:#adb5bd">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                                            <span class="small">Tidak ada pengajuan dana</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($fundRequests->hasPages())
                        <div class="d-flex align-items-center justify-content-between px-1 pt-3 border-top flex-wrap gap-2"
                            style="background:#fafafa;border-radius:0 0 1rem 1rem">
                            <span style="font-size:.75rem;color:#adb5bd">
                                Menampilkan {{ $fundRequests->firstItem() }}–{{ $fundRequests->lastItem() }} dari
                                {{ $fundRequests->total() }}
                            </span>
                            {{ $fundRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
