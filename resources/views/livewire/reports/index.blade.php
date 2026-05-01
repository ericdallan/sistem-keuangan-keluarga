{{-- resources/views/livewire/reports/index.blade.php --}}
<div class="py-2">
    <div class="container-fluid">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="px-4 py-3 text-white d-flex align-items-center justify-content-between"
                    style="background:var(--sk-primary-gradient);border-radius:1rem;box-shadow:0 4px 20px rgba(25,135,84,.25)">
                    <div>
                        <h5 class="fw-bold mb-0">Laporan Keuangan</h5>
                        <p class="mb-0 opacity-75" style="font-size:.8rem">
                            {{ $report['month_label'] }}
                        </p>
                    </div>
                    {{-- Export Button --}}
                    <button wire:click="openExportModal"
                        class="btn btn-light btn-sm rounded-pill fw-semibold d-flex align-items-center gap-2"
                        style="font-size:.8rem;padding:8px 18px">
                        <i class="bi bi-download"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>

        {{-- Export Modal --}}
        @if ($showExportModal)
            <div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);z-index:1050">
                <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
                    <div class="modal-content border-0"
                        style="border-radius:1rem;box-shadow:0 8px 32px rgba(0,0,0,.15)">

                        {{-- Header Modal --}}
                        <div class="modal-header border-0 pb-0 px-4 pt-4">
                            <h5 class="modal-title fw-bold" style="font-size:1.1rem">
                                <i class="bi bi-file-earmark-text me-2" style="color:var(--sk-primary)"></i>
                                Export Laporan
                            </h5>
                            <button wire:click="closeExportModal" type="button" class="btn-close"></button>
                        </div>

                        <div class="modal-body p-4">

                            {{-- Step 1: Format --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold"
                                    style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.05em">
                                    Format File
                                </label>
                                <div class="d-flex gap-2">
                                    <button wire:click="$set('exportFormat', 'pdf')"
                                        class="btn flex-fill fw-semibold {{ $exportFormat === 'pdf' ? 'btn-danger' : 'btn-outline-danger' }}"
                                        style="border-radius:.75rem;font-size:.85rem;padding:10px">
                                        <i class="bi bi-file-pdf me-1"></i>PDF
                                    </button>
                                    <button wire:click="$set('exportFormat', 'excel')"
                                        class="btn flex-fill fw-semibold {{ $exportFormat === 'excel' ? 'btn-success' : 'btn-outline-success' }}"
                                        style="border-radius:.75rem;font-size:.85rem;padding:10px">
                                        <i class="bi bi-file-excel me-1"></i>Excel
                                    </button>
                                </div>
                            </div>

                            {{-- Step 2: Periode --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label fw-semibold"
                                        style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.05em">
                                        Dari Bulan
                                    </label>
                                    <input type="month" wire:model="exportStartMonth" class="form-control"
                                        style="border-radius:.75rem;font-size:.85rem">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold"
                                        style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.05em">
                                        Sampai Bulan
                                    </label>
                                    <input type="month" wire:model="exportEndMonth" class="form-control"
                                        style="border-radius:.75rem;font-size:.85rem">
                                </div>
                            </div>

                            {{-- Step 3: Filter Pengguna (Admin Only) --}}
                            @if ($isAdmin)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold"
                                        style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.05em">
                                        Pengguna
                                    </label>
                                    <select wire:model="exportSelectedUser" class="form-select"
                                        style="border-radius:.75rem;font-size:.85rem">
                                        <option value="">Semua Pengguna</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Step 4: Filter Kategori --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold"
                                    style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.05em">
                                    Kategori
                                </label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                        style="background:#f8f9fa;cursor:pointer;border:2px solid {{ in_array('income', $selectedCategories) ? '#198754' : 'transparent' }}">
                                        <input type="checkbox" wire:model="selectedCategories" value="income"
                                            class="form-check-input m-0">
                                        <span style="font-size:.85rem;font-weight:500;color:#198754">
                                            <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                        </span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                        style="background:#f8f9fa;cursor:pointer;border:2px solid {{ in_array('expense', $selectedCategories) ? '#dc3545' : 'transparent' }}">
                                        <input type="checkbox" wire:model="selectedCategories" value="expense"
                                            class="form-check-input m-0">
                                        <span style="font-size:.85rem;font-weight:500;color:#dc3545">
                                            <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                        </span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                        style="background:#f8f9fa;cursor:pointer;border:2px solid {{ in_array('fund', $selectedCategories) ? '#0dcaf0' : 'transparent' }}">
                                        <input type="checkbox" wire:model="selectedCategories" value="fund"
                                            class="form-check-input m-0">
                                        <span style="font-size:.85rem;font-weight:500;color:#0dcaf0">
                                            <i class="bi bi-cash-coin me-1"></i>Pengajuan Dana
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- Info Periode --}}
                            <div class="p-3 mb-4" style="background:#f8f9fa;border-radius:.75rem">
                                <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:#6c757d">
                                    <i class="bi bi-calendar-range"></i>
                                    <span>Periode: <strong
                                            class="text-dark">{{ $this->getExportLabel() }}</strong></span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-2">
                                @if ($exportFormat === 'pdf')
                                    <button wire:click="previewPdf" wire:loading.attr="disabled"
                                        class="btn fw-semibold flex-fill"
                                        style="background:#e9ecef;color:#495057;border-radius:.75rem;padding:12px;font-size:.85rem">
                                        <span wire:loading.remove wire:target="previewPdf">
                                            <i class="bi bi-eye me-2"></i>Preview
                                        </span>
                                        <span wire:loading wire:target="previewPdf">
                                            <i class="bi bi-arrow-repeat spin me-2"></i>Memuat...
                                        </span>
                                    </button>
                                @endif

                                <button wire:click="downloadExport" wire:loading.attr="disabled"
                                    class="btn fw-bold text-white flex-fill"
                                    style="background:var(--sk-primary);border-radius:.75rem;padding:12px;font-size:.85rem">
                                    <span wire:loading.remove wire:target="downloadExport">
                                        <i class="bi bi-download me-2"></i>Download {{ strtoupper($exportFormat) }}
                                    </span>
                                    <span wire:loading wire:target="downloadExport">
                                        <i class="bi bi-arrow-repeat spin me-2"></i>Memproses...
                                    </span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Preview Modal (PDF Only) --}}
        @if ($showPreviewModal && $previewData)
            <div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,.7);z-index:1060">
                <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:900px;height:90vh">
                    <div class="modal-content border-0 h-100" style="border-radius:1rem;overflow:hidden">

                        {{-- Header --}}
                        <div class="modal-header border-0 py-3 px-4" style="background:#f8f9fa">
                            <h5 class="modal-title fw-bold" style="font-size:1rem">
                                <i class="bi bi-eye me-2" style="color:var(--sk-primary)"></i>
                                Preview PDF
                            </h5>
                            <div class="d-flex gap-2 align-items-center ms-auto">
                                <button wire:click="downloadExport" class="btn btn-sm fw-semibold text-white"
                                    style="border-radius:.5rem; background:var(--sk-primary); border:none">
                                    <i class="bi bi-download me-1"></i>Download
                                </button>
                                <button wire:click="$set('showPreviewModal', false)"
                                    class="btn btn-sm btn-outline-secondary" style="border-radius:.5rem">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Preview Content --}}
                        <div class="modal-body p-0 overflow-auto bg-white">
                            @include('exports.report-pdf-preview', ['data' => $previewData])
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                        'value' => $report['summary']['total_expense_pending'] + $report['summary']['fund_pending'],
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
                                                <div class="fw-semibold"
                                                    style="font-size:.95rem; color:var(--sk-text)">
                                                    {{ $topUser['user_name'] }}
                                                </div>
                                            </div>

                                            <!-- Total Amount -->
                                            <div class="text-end flex-shrink-0" style="min-width: 90px;">
                                                <div class="fw-bold"
                                                    style="font-size:1.05rem; color:var(--sk-primary)">
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
                                                <span class="ms-auto fw-medium"
                                                    style="color:#dc3545; font-size:.9rem">
                                                    Rp {{ number_format($topUser['expense_amount'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Baris 3: Pengajuan (full width) -->
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-cash-coin"
                                                    style="color:#0dcaf0; font-size:1.1rem"></i>
                                                <span style="color:#6c757d; font-size:.85rem">
                                                    {{ number_format($topUser['fund_count']) }} pengajuan
                                                </span>
                                                <span class="ms-auto fw-medium"
                                                    style="color:#0dcaf0; font-size:.9rem">
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
@push('styles')
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('triggerDownload', (params) => {
                const {
                    format,
                    start,
                    end,
                    user
                } = params[0];
                const url = new URL('{{ route('reports.export') }}', window.location.origin);
                url.searchParams.set('format', format);
                url.searchParams.set('start', start);
                url.searchParams.set('end', end);
                if (user) url.searchParams.set('user', user);

                window.location.href = url.toString();
            });
        });
    </script>
@endpush
