<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Collection;

// ── Sheet 1: Ringkasan Tahunan ──────────────────────────────────────────────
class ReportSummarySheet implements FromCollection, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(private string $year) {}

    public function title(): string { return 'Ringkasan'; }

    public function collection(): Collection
    {
        $income  = (float) Transaction::income()->whereYear('date', $this->year)->sum('amount');
        $expense = (float) Transaction::expense()->whereYear('date', $this->year)->sum('amount');
        $balance = $income - $expense;
        $savingsRate = $income > 0 ? round((($income - $expense) / $income) * 100, 1) : 0;
        $totalAssets = (float) Asset::sum('current_value');

        return collect([
            ['LAPORAN KEUANGAN ' . $this->year, '', ''],
            ['Dibuat pada', now()->translatedFormat('d F Y H:i'), ''],
            ['', '', ''],
            ['RINGKASAN TAHUNAN', '', ''],
            ['Keterangan', 'Nilai', ''],
            ['Total Pemasukan', $income, ''],
            ['Total Pengeluaran', $expense, ''],
            ['Saldo Bersih', $balance, ''],
            ['Rasio Tabungan', $savingsRate . '%', ''],
            ['Rata-rata Pemasukan/Bulan', round($income / 12), ''],
            ['Rata-rata Pengeluaran/Bulan', round($expense / 12), ''],
            ['Total Aset', $totalAssets, ''],
            ['Tujuan Aktif', FinancialGoal::active()->count(), ''],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Title
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Section header
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1e40af']],
                ]);

                // Column header
                $sheet->getStyle('A5:C5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '64748B']],
                ]);

                // Number format for currency rows
                foreach ([6, 7, 8, 10, 11, 12] as $row) {
                    $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $row % 2 === 0 ? 'F8FAFC' : 'FFFFFF']],
                    ]);
                }

                // Balance color
                $balance = (float) Transaction::income()->whereYear('date', $this->year)->sum('amount')
                         - (float) Transaction::expense()->whereYear('date', $this->year)->sum('amount');
                $sheet->getStyle('B8')->getFont()->getColor()->setRGB($balance >= 0 ? '16A34A' : 'DC2626');
                $sheet->getStyle('B6')->getFont()->getColor()->setRGB('16A34A');
                $sheet->getStyle('B7')->getFont()->getColor()->setRGB('DC2626');

                // Border
                $sheet->getStyle('A5:B13')->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                ]);
            },
        ];
    }
}

// ── Sheet 2: Rincian Bulanan ────────────────────────────────────────────────
class ReportMonthlySheet implements FromCollection, WithTitle, ShouldAutoSize, WithEvents
{
    private array $monthlyData;

    public function __construct(private string $year, array $monthlyData)
    {
        $this->monthlyData = $monthlyData;
    }

    public function title(): string { return 'Bulanan'; }

    public function collection(): Collection
    {
        $rows = [['Bulan', 'Pemasukan', 'Pengeluaran', 'Saldo Bersih', 'Rasio Tabungan']];

        foreach ($this->monthlyData as $row) {
            $savingRate = $row['income'] > 0
                ? round((($row['income'] - $row['expense']) / $row['income']) * 100, 1)
                : 0;
            $rows[] = [
                $row['month'],
                $row['income'],
                $row['expense'],
                $row['balance'],
                $savingRate . '%',
            ];
        }

        // Total row
        $totalIncome  = collect($this->monthlyData)->sum('income');
        $totalExpense = collect($this->monthlyData)->sum('expense');
        $totalBalance = $totalIncome - $totalExpense;
        $totalSavings = $totalIncome > 0 ? round((($totalIncome - $totalExpense) / $totalIncome) * 100, 1) : 0;
        $rows[] = ['TOTAL', $totalIncome, $totalExpense, $totalBalance, $totalSavings . '%'];

        return collect($rows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header row
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Currency format B, C, D cols
                $lastRow = count($this->monthlyData) + 2;
                $sheet->getStyle("B2:D{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

                // Zebra stripes
                for ($i = 2; $i <= $lastRow; $i++) {
                    $color = $i % 2 === 0 ? 'F8FAFC' : 'FFFFFF';
                    $sheet->getStyle("A{$i}:E{$i}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                }

                // Total row style
                $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '3B82F6']]],
                ]);

                // Color income/expense/balance cols
                $sheet->getStyle("B2:B{$lastRow}")->getFont()->getColor()->setRGB('16A34A');
                $sheet->getStyle("C2:C{$lastRow}")->getFont()->getColor()->setRGB('DC2626');

                // All borders
                $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                ]);
            },
        ];
    }
}

// ── Sheet 3: Kategori ───────────────────────────────────────────────────────
class ReportCategorySheet implements FromCollection, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(private string $year, private array $categories) {}

    public function title(): string { return 'Kategori'; }

    public function collection(): Collection
    {
        $rows = [['PEMASUKAN PER KATEGORI', '', ''], ['Kategori', 'Total', 'Jumlah Transaksi']];
        foreach ($this->categories['income'] as $cat) {
            $rows[] = [$cat['category'], $cat['total'], $cat['count']];
        }

        $rows[] = ['', '', ''];
        $rows[] = ['PENGELUARAN PER KATEGORI', '', ''];
        $rows[] = ['Kategori', 'Total', 'Jumlah Transaksi'];
        foreach ($this->categories['expense'] as $cat) {
            $rows[] = [$cat['category'], $cat['total'], $cat['count']];
        }

        return collect($rows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $incomeCount = count($this->categories['income']);
                $expenseCount = count($this->categories['expense']);

                // Income section header
                $sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16A34A']],
                ]);
                $sheet->getStyle('A2:C2')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']],
                ]);

                // Income currency
                $incomeDataEnd = 2 + $incomeCount;
                if ($incomeCount > 0) {
                    $sheet->getStyle("B3:B{$incomeDataEnd}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("B3:B{$incomeDataEnd}")->getFont()->getColor()->setRGB('16A34A');
                }

                // Expense section
                $expenseHeaderRow = $incomeDataEnd + 2;
                $expenseColHeaderRow = $expenseHeaderRow + 1;
                $sheet->getStyle("A{$expenseHeaderRow}:C{$expenseHeaderRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                ]);
                $sheet->getStyle("A{$expenseColHeaderRow}:C{$expenseColHeaderRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
                ]);

                // Expense currency
                $expenseStart = $expenseColHeaderRow + 1;
                $expenseEnd   = $expenseStart + $expenseCount - 1;
                if ($expenseCount > 0) {
                    $sheet->getStyle("B{$expenseStart}:B{$expenseEnd}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("B{$expenseStart}:B{$expenseEnd}")->getFont()->getColor()->setRGB('DC2626');
                }
            },
        ];
    }
}

// ── Main Export (Multiple Sheets) ───────────────────────────────────────────
class ReportsExport implements WithMultipleSheets
{
    public function __construct(
        private string $year,
        private array  $monthlyData,
        private array  $categories
    ) {}

    public function sheets(): array
    {
        return [
            new ReportSummarySheet($this->year),
            new ReportMonthlySheet($this->year, $this->monthlyData),
            new ReportCategorySheet($this->year, $this->categories),
        ];
    }
}
