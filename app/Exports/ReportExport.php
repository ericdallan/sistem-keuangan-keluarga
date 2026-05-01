<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    private array $summaryRow = [];
    private int $detailStartRow = 0;
    private int $lastRow = 0;

    public function __construct(
        private string $startMonth,
        private string $endMonth,
        private ?int $userId = null,
        private array $categories = ['income', 'expense', 'fund']
    ) {}

    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // Jenis
            'B' => 16,  // Tanggal
            'C' => 22,  // Pengguna
            'D' => 45,  // Keterangan
            'E' => 14,  // Status
            'F' => 18,  // Jumlah
        ];
    }

    public function array(): array
    {
        $isAdmin = Auth::user()->role === 'admin';
        $filterUserId = $isAdmin ? $this->userId : Auth::id();

        $start = Carbon::parse($this->startMonth . '-01')->startOfMonth();
        $end   = Carbon::parse($this->endMonth . '-01')->endOfMonth();

        // Fetch data
        $totalIncome  = 0;
        $totalExpense = 0;
        $totalPending = 0;
        $totalFund = 0;
        $incomeItems  = collect();
        $expenseItems = collect();
        $fundItems = collect();

        if (in_array('income', $this->categories)) {
            $incomeItems  = Income::whereBetween('date', [$start, $end])
                ->when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))
                ->with('user')->orderByDesc('date')->get();
            $totalIncome  = (float) $incomeItems->sum('amount');
        }

        if (in_array('expense', $this->categories)) {
            $expenseItems = Expense::whereBetween('date', [$start, $end])
                ->when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))
                ->with('user')->orderByDesc('date')->get();
            $totalExpense = (float) $expenseItems->where('status', 'approved')->sum('amount');
            $totalPending = (float) $expenseItems->where('status', 'pending')->sum('amount');
        }

        if (in_array('fund', $this->categories)) {
            $fundItems    = FundRequest::whereBetween('created_at', [$start, $end])
                ->when($filterUserId, fn($q) => $q->where('user_id', $filterUserId))
                ->with('user')->orderByDesc('created_at')->get();
            $totalFund    = (float) $fundItems->sum('amount');
        }

        $rows = [];

        // ── Row 1: Judul ──────────────────────────────────────────
        $rows[] = ['LAPORAN KEUANGAN', '', '', '', '', ''];   // row 1

        // ── Row 2: Periode ────────────────────────────────────────
        $rows[] = ['Periode: ' . $this->getPeriodLabel(), '', '', '', '', ''];  // row 2

        // ── Row 3: Kosong ─────────────────────────────────────────
        $rows[] = ['', '', '', '', '', ''];  // row 3

        // ── Row 4: Header Ringkasan ───────────────────────────────
        $rows[] = ['RINGKASAN', '', '', '', '', ''];  // row 4

        // ── Row 5: Label ringkasan ────────────────────────────────
        $rows[] = ['Total Pemasukan', 'Total Pengeluaran', 'Total Pending', 'Total Pengajuan Dana', '', ''];  // row 5
        $this->summaryRow = [5 => true];

        // ── Row 6: Nilai ringkasan ────────────────────────────────
        $rows[] = [$totalIncome, $totalExpense, $totalPending, $totalFund, '', ''];  // row 6

        // ── Row 7: Kosong ─────────────────────────────────────────
        $rows[] = ['', '', '', '', '', ''];  // row 7

        // ── Row 8: Header Detail ──────────────────────────────────
        $rows[] = ['DETAIL TRANSAKSI', '', '', '', '', ''];  // row 8

        // ── Row 9: Header Kolom ───────────────────────────────────
        $rows[] = ['Jenis', 'Tanggal', 'Pengguna', 'Keterangan / Alasan', 'Status', 'Jumlah'];  // row 9
        $this->detailStartRow = 9;

        // ── Row 10+: Data ─────────────────────────────────────────
        foreach ($incomeItems as $item) {
            $rows[] = [
                'Pemasukan',
                $item->date->format('d M Y'),
                $item->user->name ?? '-',
                $item->description,
                '-',
                (float) $item->amount,
            ];
        }

        foreach ($expenseItems as $item) {
            $rows[] = [
                'Pengeluaran',
                $item->date->format('d M Y'),
                $item->user->name ?? '-',
                $item->description,
                ucfirst($item->status),
                (float) -$item->amount,
            ];
        }

        foreach ($fundItems as $item) {
            $rows[] = [
                'Pengajuan Dana',
                $item->created_at->format('d M Y'),
                $item->user->name ?? '-',
                $item->reason,
                ucfirst($item->status),
                (float) $item->amount,
            ];
        }

        $this->lastRow = count($rows);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $last = $this->lastRow;

        // ── Judul (Row 1) ─────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Periode (Row 2) ───────────────────────────────────────
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 11, 'color' => ['rgb' => '6c757d']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Label RINGKASAN (Row 4) ───────────────────────────────
        $sheet->mergeCells('A4:F4');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '333333']],
        ]);

        // ── Header label ringkasan (Row 5) ────────────────────────
        $sheet->getStyle('A5:D5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '6c757d']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Nilai ringkasan (Row 6) ───────────────────────────────
        $sheet->getStyle('A6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '198754']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1E7DD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '198754']]],
        ]);
        $sheet->getStyle('B6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'dc3545']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8D7DA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'dc3545']]],
        ]);
        $sheet->getStyle('C6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '856404']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3CD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'ffc107']]],
        ]);
        $sheet->getStyle('D6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '055160']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CFF4FC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0dcaf0']]],
        ]);

        // Format angka ringkasan
        $sheet->getStyle('A6:D6')->getNumberFormat()->setFormatCode('"Rp "#,##0');

        // ── Label DETAIL (Row 8) ──────────────────────────────────
        $sheet->mergeCells('A8:F8');
        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '333333']],
        ]);

        // ── Header kolom detail (Row 9) ───────────────────────────
        $sheet->getStyle('A9:F9')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '146c43']],
            ],
        ]);
        $sheet->getRowDimension(9)->setRowHeight(22);

        // ── Data rows (Row 10+) ───────────────────────────────────
        if ($last >= 10) {
            // Zebra stripes
            for ($r = 10; $r <= $last; $r++) {
                $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F8F9FA';
                $sheet->getStyle("A{$r}:F{$r}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                    'font' => ['size' => 9],
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E9ECEF']],
                    ],
                ]);
            }

            // Warna kolom Jenis per kategori
            for ($r = 10; $r <= $last; $r++) {
                $jenis = $sheet->getCell("A{$r}")->getValue();
                $color = match ($jenis) {
                    'Pemasukan'     => '198754',
                    'Pengeluaran'   => 'dc3545',
                    'Pengajuan Dana' => '0dcaf0',
                    default         => '333333',
                };
                $sheet->getStyle("A{$r}")->getFont()->setColor(
                    (new \PhpOffice\PhpSpreadsheet\Style\Color($color))
                )->setBold(true);
            }

            // Format angka kolom Jumlah
            $sheet->getStyle("F10:F{$last}")->getNumberFormat()
                ->setFormatCode('"Rp "#,##0;[Red]"(Rp "#,##0")"');

            // Kolom Status — center
            $sheet->getStyle("E10:E{$last}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Kolom Jumlah — right align
            $sheet->getStyle("F10:F{$last}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Border outline seluruh tabel
            $sheet->getStyle("A9:F{$last}")->applyFromArray([
                'borders' => [
                    'outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DEE2E6']],
                ],
            ]);
        }

        // ── Row height default ────────────────────────────────────
        $sheet->getDefaultRowDimension()->setRowHeight(18);

        // ── Freeze pane di bawah header kolom ────────────────────
        $sheet->freezePane('A10');

        return [];
    }

    private function getPeriodLabel(): string
    {
        $start = Carbon::parse($this->startMonth . '-01');
        $end   = Carbon::parse($this->endMonth . '-01');

        return $this->startMonth === $this->endMonth
            ? $start->translatedFormat('F Y')
            : $start->translatedFormat('M Y') . ' - ' . $end->translatedFormat('M Y');
    }
}
