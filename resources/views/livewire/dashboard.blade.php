<div class="py-2">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4 text-white"
                    style="background:var(--sk-primary-gradient);border-radius:1rem;box-shadow:0 4px 20px rgba(25,135,84,.25)">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}!</h4>
                            <p class="mb-0 opacity-75" style="font-size:.9rem">
                                Ringkasan keuangan keluarga — {{ $summary['current_month_label'] }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('income.create') }}"
                                    class="btn btn-light rounded-pill d-flex align-items-center gap-2 fw-semibold"
                                    style="font-size:.8rem;color:var(--sk-primary)">
                                    <i class="bi bi-plus-circle"></i> Pemasukan
                                </a>
                            @endif
                            <a href="{{ route('expenses.create') }}"
                                class="btn btn-outline-light rounded-pill d-flex align-items-center gap-2 fw-semibold"
                                style="font-size:.8rem">
                                <i class="bi bi-plus-circle"></i> Pengeluaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            {{-- Total Saldo --}}
            <div class="col-md-4">
                <div class="h-100 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:44px;height:44px;background:var(--sk-primary-light);color:var(--sk-primary);font-size:1.2rem">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <span class="fw-semibold"
                            style="font-size:.75rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">
                            Total Saldo
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0" style="font-size:1.5rem;color:var(--sk-text)">
                        Rp {{ number_format($summary['balance'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;color:#adb5bd">
                        <i class="bi bi-info-circle"></i> Akumulasi semua pemasukan - pengeluaran
                    </div>
                </div>
            </div>

            {{-- Pemasukan Bulan Ini --}}
            <div class="col-md-4">
                <div class="h-100 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:44px;height:44px;background:#d1e7dd;color:#198754;font-size:1.2rem">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <span class="fw-semibold"
                            style="font-size:.75rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">
                            Pemasukan
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0" style="font-size:1.5rem;color:#198754">
                        Rp {{ number_format($summary['total_income_month'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-2" style="font-size:.75rem;color:#adb5bd">
                        {{ $summary['current_month_label'] }}
                    </div>
                </div>
            </div>

            {{-- Pengeluaran Bulan Ini --}}
            <div class="col-md-4">
                <div class="h-100 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:44px;height:44px;background:#f8d7da;color:#dc3545;font-size:1.2rem">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <span class="fw-semibold"
                            style="font-size:.75rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">
                            Pengeluaran
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0" style="font-size:1.5rem;color:#dc3545">
                        Rp {{ number_format($summary['total_expense_month'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-2" style="font-size:.75rem;color:#adb5bd">
                        {{ $summary['current_month_label'] }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin: Alert Pending Approval --}}
        @if (auth()->user()->role === 'admin' && ($summary['pending_expenses'] > 0 || $summary['pending_fund_requests'] > 0))
            <div class="d-flex align-items-center gap-3 mb-4 p-3"
                style="background:#fff3cd;border-radius:1rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid #ffc107">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width:36px;height:36px;background:#ffc107;color:#fff;font-size:1rem">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="fw-semibold" style="font-size:.85rem;color:#856404">
                        Ada <strong>{{ $summary['pending_expenses'] }} pengeluaran</strong> dan
                        <strong>{{ $summary['pending_fund_requests'] }} pengajuan dana</strong> menunggu persetujuan.
                    </span>
                </div>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm rounded-pill fw-semibold flex-shrink-0"
                    style="background:#ffc107;color:#fff;font-size:.75rem;padding:5px 14px">
                    Proses
                </a>
            </div>
        @endif

        {{-- User: Alert Status Pengajuan  --}}
        @if (auth()->user()->role === 'user' && isset($summary['my_pending_expenses']) && $summary['my_pending_expenses'] > 0)
            <div class="d-flex align-items-center gap-3 mb-4 p-3"
                style="background:#cff4fc;border-radius:1rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid #0dcaf0">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width:36px;height:36px;background:#0dcaf0;color:#fff;font-size:1rem">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="fw-semibold" style="font-size:.85rem;color:#055160">
                        Kamu memiliki <strong>{{ $summary['my_pending_expenses'] }} pengeluaran</strong> yang masih
                        pending.
                    </span>
                </div>
                <a href="{{ route('my-expenses.index') }}" class="btn btn-sm rounded-pill fw-semibold flex-shrink-0"
                    style="background:#0dcaf0;color:#fff;font-size:.75rem;padding:5px 14px">
                    Cek Status
                </a>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="h-100 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="font-size:1rem">Grafik Keuangan 12 Bulan Terakhir</h5>
                        <span class="rounded-pill px-3 py-1 fw-semibold"
                            style="font-size:.7rem;background:var(--sk-primary-light);color:var(--sk-primary)">
                            {{ $summary['current_month_label'] }}
                        </span>
                    </div>
                    <div style="height:300px">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="h-100 p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <h5 class="fw-bold mb-3" style="font-size:1rem">Aksi Cepat</h5>
                    <div class="d-grid gap-2">

                        {{-- Admin Only: Tambah Pemasukan --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('income.create') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='var(--sk-primary-light)';this.querySelector('span').style.color='var(--sk-primary)'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:var(--sk-primary-light);color:var(--sk-primary)">
                                    <i class="bi bi-arrow-down-circle"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Tambah Pemasukan
                                </span>
                            </a>
                        @endif

                        {{-- Admin Only: Approval Pengeluaran --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('expenses.index') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#f8d7da';this.querySelector('span').style.color='#842029'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#f8d7da;color:#dc3545">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Approval Pengeluaran
                                </span>
                            </a>
                        @endif

                        {{-- Admin Only: Approval Pengajuan Dana --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('fund-requests.index') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#cff4fc';this.querySelector('span').style.color='#055160'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#cff4fc;color:#055160">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Approval Pengajuan Dana
                                </span>
                            </a>
                        @endif

                        {{-- Admin Only: Kelola Pengguna --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#e2e3e5';this.querySelector('span').style.color='#41464b'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#e2e3e5;color:#41464b">
                                    <i class="bi bi-people"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Kelola Pengguna
                                </span>
                            </a>
                        @endif

                        {{-- User Only: Catat Pengeluaran --}}
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('expenses.create') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#f8d7da';this.querySelector('span').style.color='#842029'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#f8d7da;color:#dc3545">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Catat Pengeluaran
                                </span>
                            </a>
                        @endif

                        {{-- User Only: Ajukan Dana --}}
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('fund-requests.create') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#cff4fc';this.querySelector('span').style.color='#055160'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#cff4fc;color:#055160">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Ajukan Dana
                                </span>
                            </a>
                        @endif

                        {{-- User Only: Pengeluaran Saya --}}
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('my-expenses.index') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='#e2e3e5';this.querySelector('span').style.color='#41464b'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:#e2e3e5;color:#41464b">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Pengeluaran Saya
                                </span>
                            </a>
                        @endif

                        {{-- User Only: Pengajuan Dana Saya --}}
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('my-fund-requests.index') }}"
                                class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:#f8f9fa;border-radius:.75rem;transition:all .15s"
                                onmouseover="this.style.background='var(--sk-primary-light)';this.querySelector('span').style.color='var(--sk-primary)'"
                                onmouseout="this.style.background='#f8f9fa';this.querySelector('span').style.color='var(--sk-text)'">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:36px;height:36px;background:var(--sk-primary-light);color:var(--sk-primary)">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <span class="fw-semibold"
                                    style="font-size:.85rem;color:var(--sk-text);transition:color .15s">
                                    Pengajuan Dana Saya
                                </span>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity  --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="p-4" style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="font-size:1rem">
                            {{ auth()->user()->role === 'admin' ? 'Aktivitas Pengeluaran Terbaru' : 'Pengeluaran Saya Terbaru' }}
                        </h5>
                        <a href="{{ auth()->user()->role === 'admin' ? route('expenses.index') : route('my-expenses.index') }}"
                            class="text-decoration-none fw-semibold" style="font-size:.8rem;color:var(--sk-primary)">
                            Lihat Semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead style="background:#f8f9fa">
                                <tr>
                                    <th class="ps-4 py-3"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                                        Tanggal</th>
                                    <th class="py-3"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                                        {{ auth()->user()->role === 'admin' ? 'User' : 'Kategori' }}</th>
                                    <th class="py-3"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                                        Keterangan</th>
                                    <th class="py-3"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                                        Status</th>
                                    <th class="pe-4 py-3 text-end"
                                        style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['recent_expenses'] as $expense)
                                    <tr style="border-bottom:1px solid rgba(0,0,0,.04)"
                                        onmouseover="this.style.background='#f8f9fa'"
                                        onmouseout="this.style.background=''">
                                        <td class="ps-4 py-3" style="font-size:.85rem;color:#6c757d">
                                            {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="sk-avatar"
                                                    style="width:28px;height:28px;font-size:.75rem">
                                                    {{ strtoupper(substr($expense->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="fw-semibold" style="font-size:.85rem">
                                                    {{ auth()->user()->role === 'admin' ? $expense->user->name ?? 'Unknown' : $expense->category ?? 'Umum' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3"
                                            style="font-size:.85rem;color:var(--sk-text);max-width:250px"
                                            class="text-truncate">
                                            {{ $expense->description }}
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $statusStyle = match ($expense->status) {
                                                    'approved' => 'background:#d1e7dd;color:#0f5132',
                                                    'rejected' => 'background:#f8d7da;color:#842029',
                                                    default => 'background:#fff3cd;color:#856404',
                                                };
                                                $statusIcon = match ($expense->status) {
                                                    'approved' => 'bi-check-circle',
                                                    'rejected' => 'bi-x-circle',
                                                    default => 'bi-hourglass-split',
                                                };
                                            @endphp
                                            <span
                                                class="rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1"
                                                style="font-size:.7rem;{{ $statusStyle }}">
                                                <i class="bi {{ $statusIcon }}"></i>
                                                {{ ucfirst($expense->status) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <span class="fw-bold" style="font-size:.9rem;color:#dc3545">
                                                -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5" style="color:#adb5bd">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem"></i>
                                            Belum ada aktivitas pengeluaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['months']),
                datasets: [{
                        label: 'Pemasukan',
                        data: @json($chartData['incomes']),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#198754',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($chartData['expenses']),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Figtree'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#2d3436',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 13,
                            family: 'Figtree'
                        },
                        bodyFont: {
                            size: 12,
                            family: 'Figtree'
                        },
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString(
                                    'id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: 'Figtree'
                            },
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: 'Figtree'
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
