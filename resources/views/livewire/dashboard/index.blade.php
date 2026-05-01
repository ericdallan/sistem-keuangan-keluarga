<div class="py-2">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="px-4 py-3 text-white"
                    style="background:var(--sk-primary-gradient);border-radius:1rem;box-shadow:0 4px 20px rgba(25,135,84,.25)">
                    <h5 class="fw-bold mb-0">Halo, {{ auth()->user()->name }}!</h5>
                    <p class="mb-0 opacity-75" style="font-size:.8rem">
                        Ringkasan keuangan keluarga — {{ $summary['current_month_label'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            {{-- Total Saldo --}}
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 px-4 py-3"
                    style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:40px;height:40px;background:var(--sk-primary-light);color:var(--sk-primary);font-size:1.1rem">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div
                            style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                            Total Saldo
                        </div>
                        <div class="fw-bold" style="font-size:1.1rem;color:var(--sk-text)">
                            Rp {{ number_format($summary['balance'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pemasukan Bulan Ini --}}
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 px-4 py-3"
                    style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:40px;height:40px;background:#d1e7dd;color:#198754;font-size:1.1rem">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div>
                        <div
                            style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                            Pemasukan — {{ $summary['current_month_label'] }}
                        </div>
                        <div class="fw-bold" style="font-size:1.1rem;color:#198754">
                            Rp {{ number_format($summary['total_income_month'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengeluaran Bulan Ini --}}
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 px-4 py-3"
                    style="background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:40px;height:40px;background:#f8d7da;color:#dc3545;font-size:1.1rem">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                    <div>
                        <div
                            style="font-size:.7rem;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em;font-weight:600">
                            Pengeluaran — {{ $summary['current_month_label'] }}
                        </div>
                        <div class="fw-bold" style="font-size:1.1rem;color:#dc3545">
                            Rp {{ number_format($summary['total_expense_month'], 0, ',', '.') }}
                        </div>
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
                {{-- Arahkan ke halaman yang punya pending --}}
                @if ($summary['pending_expenses'] > 0)
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm rounded-pill fw-semibold flex-shrink-0"
                        style="background:#ffc107;color:#fff;font-size:.75rem;padding:5px 14px">
                        Proses Pengeluaran
                    </a>
                @endif
                @if ($summary['pending_fund_requests'] > 0)
                    <a href="{{ route('fund-requests.index') }}"
                        class="btn btn-sm rounded-pill fw-semibold flex-shrink-0"
                        style="background:#fd7e14;color:#fff;font-size:.75rem;padding:5px 14px">
                        Proses Pengajuan Dana
                    </a>
                @endif
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
                <a href="{{ route('expenses.index') }}" class="btn btn-sm rounded-pill fw-semibold flex-shrink-0"
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
                        @if (auth()->user()->isAdmin())
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
                        @if (auth()->user()->isAdmin())
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
                        @if (auth()->user()->isAdmin())
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
                        @if (auth()->user()->isAdmin())
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
                        @if (!auth()->user()->isAdmin())
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
                        @if (!auth()->user()->isAdmin())
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
                        @if (!auth()->user()->isAdmin())
                            <a href="{{ route('expenses.index') }}"
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
                        @if (!auth()->user()->isAdmin())
                            <a href="{{ route('fund-requests.index') }}"
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
        <div class="mt-4">
            <livewire:dashboard.recent-activity />
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            let chartInstance = null;

            function initChart() {
                const canvas = document.getElementById('financeChart');
                if (!canvas) return;

                // Hancurkan chart lama jika ada
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }

                const ctx = canvas.getContext('2d');
                chartInstance = new Chart(ctx, {
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
                                        return context.dataset.label + ': Rp ' +
                                            context.parsed.y.toLocaleString('id-ID');
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
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) +
                                            'jt';
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
            }

            // Inisialisasi pertama kali (hard load)
            document.addEventListener('DOMContentLoaded', initChart);

            // Inisialisasi ulang setiap kali wire:navigate selesai
            document.addEventListener('livewire:navigated', initChart);
        })();
    </script>
@endpush
