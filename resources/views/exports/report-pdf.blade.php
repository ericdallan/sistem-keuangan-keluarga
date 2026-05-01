<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #198754;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 6px;
            color: #198754;
        }

        .header .period {
            font-size: 12px;
            color: #555;
        }

        .header .user {
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* SUMMARY */
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .summary-item {
            width: 25%;
            display: table-cell;
            margin-right: 1%;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            border-top: 3px solid;
            text-align: center;
        }

        .summary-box {
            background: #f8f9fa;
            border-radius: 6px;
            border-top: 3px solid;
            text-align: center;
            padding: 10px;
        }

        .summary-item:last-child {
            margin-right: 0;
        }

        .income {
            border-color: #198754;
        }

        .expense {
            border-color: #dc3545;
        }

        .pending {
            border-color: #ffc107;
        }

        .fund {
            border-color: #0dcaf0;
        }

        .label {
            font-size: 9px;
            color: #777;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .value {
            font-size: 14px;
            font-weight: bold;
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

        /* SECTION */
        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        .section-count {
            font-size: 10px;
            float: right;
            color: #666;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #e9ecef;
        }

        th {
            padding: 8px;
            font-size: 9px;
            text-align: left;
            border-bottom: 2px solid #ced4da;
        }

        td {
            padding: 10px 6px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: bold;
        }

        /* BADGE */
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8.5px;
            font-weight: bold;
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

        /* EMPTY */
        .empty {
            text-align: center;
            padding: 15px;
            font-style: italic;
            color: #999;
        }

        /* FOOTER */
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
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

    {{-- SUMMARY --}}
    <div class="summary-grid">

        <div class="summary-item">
            <div class="summary-box income">
                <div class="label">Pemasukan</div>
                <div class="value text-success">
                    Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-box expense">
                <div class="label">Pengeluaran</div>
                <div class="value text-danger">
                    Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-box pending">
                <div class="label">Pending</div>
                <div class="value text-warning">
                    Rp {{ number_format($summary['total_pending'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-box fund">
                <div class="label">Pengajuan Dana</div>
                <div class="value text-info">
                    Rp {{ number_format($summary['total_fund'], 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>

    {{-- PEMASUKAN --}}
    <div class="section">
        <div class="section-title">
            Detail Pemasukan
            <span class="section-count">{{ $incomes->count() }} transaksi</span>
        </div>

        @if ($incomes->count())
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pengguna</th>
                        <th>Keterangan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomes as $item)
                        <tr>
                            <td>{{ $item->date->format('d M Y') }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="text-right amount text-success">
                                +Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">Tidak ada data</div>
        @endif
    </div>

    {{-- PENGELUARAN --}}
    <div class="section">
        <div class="section-title">
            Detail Pengeluaran
            <span class="section-count">{{ $expenses->count() }} transaksi</span>
        </div>

        @if ($expenses->count())
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pengguna</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $item)
                        <tr>
                            <td>{{ $item->date->format('d M Y') }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <span class="badge">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="text-right amount text-danger">
                                -Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">Tidak ada data</div>
        @endif
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} | {{ config('app.name') }}
    </div>

</body>

</html>
