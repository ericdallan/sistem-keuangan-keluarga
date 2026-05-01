<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #198754;
        }

        .header h1 {
            font-size: 18px;
            color: #198754;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .header .period {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .header .user {
            font-size: 10px;
            font-weight: 600;
            color: #495057;
        }

        /* Summary Grid */
        .summary-section {
            margin-bottom: 20px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 12px 8px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid;
        }

        .summary-item.income {
            border-left-color: #198754;
        }

        .summary-item.expense {
            border-left-color: #dc3545;
        }

        .summary-item.pending {
            border-left-color: #ffc107;
        }

        .summary-item.fund {
            border-left-color: #0dcaf0;
        }

        .summary-item .label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .summary-item .value {
            font-size: 13px;
            font-weight: 700;
        }

        .text-success {
            color: #198754;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #856404;
        }

        .text-info {
            color: #055160;
        }

        /* Section */
        .section {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #e9ecef;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: 700;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .section-count {
            margin-left: auto;
            font-size: 9px;
            color: #6c757d;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        thead tr {
            background: #f8f9fa;
        }

        th {
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1.5px solid #dee2e6;
        }

        th.text-right {
            text-align: right;
        }

        td {
            padding: 8px 6px;
            border-bottom: 0.5px solid #e9ecef;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* Amount */
        .amount {
            font-weight: 600;
            font-size: 9.5px;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
        }

        .badge-success {
            background: #d1e7dd;
            color: #198754;
        }

        .badge-danger {
            background: #f8d7da;
            color: #dc3545;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #cff4fc;
            color: #055160;
        }

        /* Empty */
        .empty {
            text-align: center;
            padding: 20px;
            color: #adb5bd;
            font-style: italic;
            font-size: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 0.5px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #adb5bd;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <div class="period">
            @if ($start->format('Y-m') === $end->format('Y-m'))
                {{ $start->translatedFormat('F Y') }}
            @else
                {{ $start->translatedFormat('d M Y') }} - {{ $end->translatedFormat('d M Y') }}
            @endif
        </div>
        @if ($filterUserName)
            <div class="user">Pengguna: {{ $filterUserName }}</div>
        @endif
    </div>

    {{-- Summary --}}
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item income">
                <div class="label">Pemasukan</div>
                <div class="value text-success">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item expense">
                <div class="label">Pengeluaran</div>
                <div class="value text-danger">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item pending">
                <div class="label">Pending</div>
                <div class="value text-warning">Rp {{ number_format($summary['total_pending'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item fund">
                <div class="label">Pengajuan Dana</div>
                <div class="value text-info">Rp {{ number_format($summary['total_fund'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Pemasukan --}}
    @if (in_array('income', $categories ?? ['income', 'expense', 'fund']))
        <div class="section">
            <div class="section-header">
                <span class="section-icon" style="background:#198754">↓</span>
                <h3 class="section-title">Detail Pemasukan</h3>
                <span class="section-count">{{ $incomes->count() }} transaksi</span>
            </div>

            @if ($incomes->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width:18%">Tanggal</th>
                            <th style="width:22%">Pengguna</th>
                            <th>Keterangan</th>
                            <th class="text-right" style="width:20%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $income)
                            <tr>
                                <td class="nowrap" style="color:#6c757d">{{ $income->date->format('d M Y') }}</td>
                                <td style="font-weight:500">{{ $income->user->name ?? '-' }}</td>
                                <td>{{ $income->description }}</td>
                                <td class="text-right amount text-success">+Rp
                                    {{ number_format($income->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">Tidak ada data pemasukan</div>
            @endif
        </div>
    @endif

    {{-- Pengeluaran --}}
    @if (in_array('expense', $categories ?? ['income', 'expense', 'fund']))
        <div class="section">
            <div class="section-header">
                <span class="section-icon" style="background:#dc3545">↑</span>
                <h3 class="section-title">Detail Pengeluaran</h3>
                <span class="section-count">{{ $expenses->count() }} transaksi</span>
            </div>

            @if ($expenses->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width:15%">Tanggal</th>
                            <th style="width:18%">Pengguna</th>
                            <th>Keterangan</th>
                            <th style="width:12%">Status</th>
                            <th class="text-right" style="width:18%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            @php
                                $badgeClass = match ($expense->status) {
                                    'approved' => 'badge-success',
                                    'rejected' => 'badge-danger',
                                    default => 'badge-warning',
                                };
                                $badgeLabel = match ($expense->status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending',
                                };
                            @endphp
                            <tr>
                                <td class="nowrap" style="color:#6c757d">{{ $expense->date->format('d M Y') }}</td>
                                <td style="font-weight:500">{{ $expense->user->name ?? '-' }}</td>
                                <td>{{ $expense->description }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                                <td class="text-right amount text-danger">-Rp
                                    {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">Tidak ada data pengeluaran</div>
            @endif
        </div>
    @endif

    {{-- Pengajuan Dana --}}
    @if (in_array('fund', $categories ?? ['income', 'expense', 'fund']))
        <div class="section">
            <div class="section-header">
                <span class="section-icon" style="background:#0dcaf0">$</span>
                <h3 class="section-title">Detail Pengajuan Dana</h3>
                <span class="section-count">{{ $fundRequests->count() }} transaksi</span>
            </div>

            @if ($fundRequests->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width:15%">Tanggal</th>
                            <th style="width:18%">Pengguna</th>
                            <th>Alasan</th>
                            <th style="width:12%">Status</th>
                            <th class="text-right" style="width:18%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fundRequests as $fund)
                            @php
                                $badgeClass = match ($fund->status) {
                                    'approved' => 'badge-success',
                                    'rejected' => 'badge-danger',
                                    default => 'badge-info',
                                };
                                $badgeLabel = match ($fund->status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending',
                                };
                            @endphp
                            <tr>
                                <td class="nowrap" style="color:#6c757d">{{ $fund->created_at->format('d M Y') }}</td>
                                <td style="font-weight:500">{{ $fund->user->name ?? '-' }}</td>
                                <td>{{ $fund->reason }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                                <td class="text-right amount text-info">+Rp
                                    {{ number_format($fund->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">Tidak ada data pengajuan dana</div>
            @endif
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} | {{ config('app.name') }}
    </div>

</body>

</html>
