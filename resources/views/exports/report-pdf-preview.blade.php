@php
    $start = $data['start'];
    $end = $data['end'];
    $incomes = $data['incomes'] ?? collect();
    $expenses = $data['expenses'] ?? collect();
    $fundRequests = $data['fundRequests'] ?? collect();
    $summary = $data['summary'];
    $categories = $data['categories'] ?? ['income', 'expense', 'fund'];
    $filterUserName = $data['filterUserName'] ?? null;
@endphp

<div style="max-width:800px;margin:0 auto;padding:40px;background:#fff">

    {{-- Header --}}
    <div style="text-align:center;margin-bottom:30px;padding-bottom:20px;border-bottom:3px solid #198754">
        <h1 style="font-size:24px;color:#198754;margin-bottom:8px;font-weight:700">Laporan Keuangan</h1>
        <p style="font-size:13px;color:#666;margin-bottom:5px">
            @if ($start->format('Y-m') === $end->format('Y-m'))
                {{ $start->translatedFormat('F Y') }}
            @else
                {{ $start->translatedFormat('d M Y') }} - {{ $end->translatedFormat('d M Y') }}
            @endif
        </p>
        @if ($filterUserName)
            <p style="font-size:12px;font-weight:600;color:#495057">Pengguna: {{ $filterUserName }}</p>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:15px;margin-bottom:30px">
        <div style="background:#f8f9fa;border-radius:12px;padding:20px;text-align:center;border-left:4px solid #198754">
            <div style="font-size:10px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                Total Pemasukan</div>
            <div style="font-size:18px;font-weight:700;color:#198754">Rp
                {{ number_format($summary['total_income'], 0, ',', '.') }}</div>
        </div>
        <div style="background:#f8f9fa;border-radius:12px;padding:20px;text-align:center;border-left:4px solid #dc3545">
            <div style="font-size:10px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                Total Pengeluaran</div>
            <div style="font-size:18px;font-weight:700;color:#dc3545">Rp
                {{ number_format($summary['total_expense'], 0, ',', '.') }}</div>
        </div>
        <div style="background:#f8f9fa;border-radius:12px;padding:20px;text-align:center;border-left:4px solid #ffc107">
            <div style="font-size:10px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                Pending</div>
            <div style="font-size:18px;font-weight:700;color:#856404">Rp
                {{ number_format($summary['total_pending'], 0, ',', '.') }}</div>
        </div>
        <div style="background:#f8f9fa;border-radius:12px;padding:20px;text-align:center;border-left:4px solid #0dcaf0">
            <div style="font-size:10px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                Pengajuan Dana</div>
            <div style="font-size:18px;font-weight:700;color:#055160">Rp
                {{ number_format($summary['total_fund'], 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Pemasukan --}}
    @if (in_array('income', $categories))
        <div style="margin-bottom:30px">
            <div
                style="display:flex;align-items:center;gap:10px;margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid #e9ecef">
                <div
                    style="width:32px;height:32px;border-radius:50%;background:#198754;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px">
                    ↓</div>
                <h3 style="font-size:16px;font-weight:700;color:#333;margin:0">Detail Pemasukan</h3>
                <span style="margin-left:auto;font-size:12px;color:#6c757d">{{ $incomes->count() }} transaksi</span>
            </div>

            @if ($incomes->count() > 0)
                <table style="width:100%;border-collapse:collapse;font-size:12px">
                    <thead>
                        <tr style="background:#f8f9fa">
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Tanggal</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Pengguna</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Keterangan</th>
                            <th
                                style="padding:12px;text-align:right;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $income)
                            <tr style="border-bottom:1px solid #e9ecef">
                                <td style="padding:12px;color:#6c757d;white-space:nowrap">
                                    {{ $income->date->format('d M Y') }}</td>
                                <td style="padding:12px;font-weight:500">{{ $income->user->name ?? '-' }}</td>
                                <td style="padding:12px;color:#333">{{ $income->description }}</td>
                                <td
                                    style="padding:12px;text-align:right;font-weight:600;color:#198754;white-space:nowrap">
                                    +Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center;padding:30px;color:#adb5bd;font-style:italic">Tidak ada data pemasukan
                </div>
            @endif
        </div>
    @endif

    {{-- Pengeluaran --}}
    @if (in_array('expense', $categories))
        <div style="margin-bottom:30px">
            <div
                style="display:flex;align-items:center;gap:10px;margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid #e9ecef">
                <div
                    style="width:32px;height:32px;border-radius:50%;background:#dc3545;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px">
                    ↑</div>
                <h3 style="font-size:16px;font-weight:700;color:#333;margin:0">Detail Pengeluaran</h3>
                <span style="margin-left:auto;font-size:12px;color:#6c757d">{{ $expenses->count() }} transaksi</span>
            </div>

            @if ($expenses->count() > 0)
                <table style="width:100%;border-collapse:collapse;font-size:12px">
                    <thead>
                        <tr style="background:#f8f9fa">
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Tanggal</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Pengguna</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Keterangan</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Status</th>
                            <th
                                style="padding:12px;text-align:right;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            @php
                                $badgeStyle = match ($expense->status) {
                                    'approved' => 'background:#d1e7dd;color:#198754;',
                                    'rejected' => 'background:#f8d7da;color:#dc3545;',
                                    default => 'background:#fff3cd;color:#856404;',
                                };
                                $badgeLabel = match ($expense->status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending',
                                };
                            @endphp
                            <tr style="border-bottom:1px solid #e9ecef">
                                <td style="padding:12px;color:#6c757d;white-space:nowrap">
                                    {{ $expense->date->format('d M Y') }}</td>
                                <td style="padding:12px;font-weight:500">{{ $expense->user->name ?? '-' }}</td>
                                <td style="padding:12px;color:#333">{{ $expense->description }}</td>
                                <td style="padding:12px">
                                    <span
                                        style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:600;{{ $badgeStyle }}">{{ $badgeLabel }}</span>
                                </td>
                                <td
                                    style="padding:12px;text-align:right;font-weight:600;color:#dc3545;white-space:nowrap">
                                    -Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center;padding:30px;color:#adb5bd;font-style:italic">Tidak ada data pengeluaran
                </div>
            @endif
        </div>
    @endif

    {{-- Pengajuan Dana --}}
    @if (in_array('fund', $categories))
        <div style="margin-bottom:30px">
            <div
                style="display:flex;align-items:center;gap:10px;margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid #e9ecef">
                <div
                    style="width:32px;height:32px;border-radius:50%;background:#0dcaf0;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px">
                    $</div>
                <h3 style="font-size:16px;font-weight:700;color:#333;margin:0">Detail Pengajuan Dana</h3>
                <span style="margin-left:auto;font-size:12px;color:#6c757d">{{ $fundRequests->count() }}
                    transaksi</span>
            </div>

            @if ($fundRequests->count() > 0)
                <table style="width:100%;border-collapse:collapse;font-size:12px">
                    <thead>
                        <tr style="background:#f8f9fa">
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Tanggal</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Pengguna</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Alasan</th>
                            <th
                                style="padding:12px;text-align:left;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Status</th>
                            <th
                                style="padding:12px;text-align:right;font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #dee2e6">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fundRequests as $fund)
                            @php
                                $badgeStyle = match ($fund->status) {
                                    'approved' => 'background:#d1e7dd;color:#198754;',
                                    'rejected' => 'background:#f8d7da;color:#dc3545;',
                                    default => 'background:#cff4fc;color:#055160;',
                                };
                                $badgeLabel = match ($fund->status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending',
                                };
                            @endphp
                            <tr style="border-bottom:1px solid #e9ecef">
                                <td style="padding:12px;color:#6c757d;white-space:nowrap">
                                    {{ $fund->created_at->format('d M Y') }}</td>
                                <td style="padding:12px;font-weight:500">{{ $fund->user->name ?? '-' }}</td>
                                <td style="padding:12px;color:#333">{{ $fund->reason }}</td>
                                <td style="padding:12px">
                                    <span
                                        style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:600;{{ $badgeStyle }}">{{ $badgeLabel }}</span>
                                </td>
                                <td
                                    style="padding:12px;text-align:right;font-weight:600;color:#0dcaf0;white-space:nowrap">
                                    +Rp {{ number_format($fund->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center;padding:30px;color:#adb5bd;font-style:italic">Tidak ada data pengajuan
                    dana</div>
            @endif
        </div>
    @endif

    {{-- Footer --}}
    <div
        style="margin-top:40px;padding-top:20px;border-top:1px solid #dee2e6;text-align:center;font-size:10px;color:#adb5bd">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} | {{ config('app.name') }}
    </div>

</div>
