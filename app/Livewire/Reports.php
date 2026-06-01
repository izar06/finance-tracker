<?php

namespace App\Livewire;

use App\Exports\ReportsExport;
use App\Models\Asset;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Component
{
    public string $year;
    public string $reportType  = 'monthly'; // monthly | category | comparison
    public string $filterMonth = '';        // '' = semua bulan, '1'-'12' = bulan spesifik
    public string $monthFrom   = '';        // rentang: dari bulan
    public string $monthTo     = '';        // rentang: sampai bulan
    public string $rangeMode   = 'year';    // year | month | range

    public function mount(): void
    {
        $this->year      = (string) now()->year;
        $this->monthFrom = '1';
        $this->monthTo   = '12';
    }

    // Reset pagination saat filter berubah
    public function updatedRangeMode(): void { $this->filterMonth = ''; $this->monthFrom = '1'; $this->monthTo = '12'; }
    public function updatedFilterMonth(): void {}
    public function updatedMonthFrom(): void {}
    public function updatedMonthTo(): void {}

    // Helper: list bulan yang aktif untuk query
    protected function activeMonths(): array
    {
        if ($this->rangeMode === 'month' && $this->filterMonth) {
            return [(int)$this->filterMonth];
        }
        if ($this->rangeMode === 'range' && $this->monthFrom && $this->monthTo) {
            return range((int)$this->monthFrom, (int)$this->monthTo);
        }
        return range(1, 12); // year mode: semua bulan
    }

    public function getRangeLabelProperty(): string
    {
        $months = \Carbon\Carbon::create()->locale('id');
        if ($this->rangeMode === 'month' && $this->filterMonth) {
            return \Carbon\Carbon::create($this->year, $this->filterMonth, 1)->translatedFormat('F Y');
        }
        if ($this->rangeMode === 'range' && $this->monthFrom && $this->monthTo) {
            $from = \Carbon\Carbon::create($this->year, $this->monthFrom, 1)->translatedFormat('M');
            $to   = \Carbon\Carbon::create($this->year, $this->monthTo,   1)->translatedFormat('M Y');
            return "$from – $to";
        }
        return "Tahun {$this->year}";
    }

    public function getAvailableYearsProperty(): array
    {
        $years = Transaction::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn($y) => (string) $y)
            ->toArray();

        return $years ?: [(string) now()->year];
    }

    public function getMonthlyBreakdownProperty(): array
    {
        $activeMonths = $this->activeMonths();
        $rows = [];
        foreach ($activeMonths as $m) {
            $income = (float) Transaction::income()
                ->whereYear('date', $this->year)
                ->whereMonth('date', $m)
                ->sum('amount');

            $expense = (float) Transaction::expense()
                ->whereYear('date', $this->year)
                ->whereMonth('date', $m)
                ->sum('amount');

            $rows[] = [
                'month'   => \Carbon\Carbon::create($this->year, $m, 1)->translatedFormat('F'),
                'income'  => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        }
        return $rows;
    }

    public function getCategoryBreakdownProperty(): array
    {
        $activeMonths = $this->activeMonths();

        $income = Transaction::income()
            ->whereYear('date', $this->year)
            ->whereIn(\DB::raw('MONTH(date)'), $activeMonths)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        $expense = Transaction::expense()
            ->whereYear('date', $this->year)
            ->whereIn(\DB::raw('MONTH(date)'), $activeMonths)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        return compact('income', 'expense');
    }

    public function getYearlySummaryProperty(): array
    {
        $activeMonths = $this->activeMonths();

        $income  = (float) Transaction::income()
            ->whereYear('date', $this->year)
            ->whereIn(\DB::raw('MONTH(date)'), $activeMonths)
            ->sum('amount');
        $expense = (float) Transaction::expense()
            ->whereYear('date', $this->year)
            ->whereIn(\DB::raw('MONTH(date)'), $activeMonths)
            ->sum('amount');

        $monthCount = count($activeMonths) ?: 1;

        return [
            'income'               => $income,
            'expense'              => $expense,
            'balance'              => $income - $expense,
            'savings_rate'         => $income > 0 ? round((($income - $expense) / $income) * 100, 1) : 0,
            'avg_monthly_income'   => round($income / $monthCount),
            'avg_monthly_expense'  => round($expense / $monthCount),
            'total_assets'         => (float) Asset::sum('current_value'),
            'active_goals'         => FinancialGoal::active()->count(),
        ];
    }

    public function getChartDataProperty(): array
    {
        $months  = [];
        $incomes = [];
        $expenses = [];
        $balances = [];

        foreach ($this->monthlyBreakdown as $row) {
            $months[]   = substr($row['month'], 0, 3);
            $incomes[]  = $row['income'];
            $expenses[] = $row['expense'];
            $balances[] = $row['balance'];
        }

        return compact('months', 'incomes', 'expenses', 'balances');
    }

    protected $listeners = [
        'receiveChartImages' => 'receiveChartImages',
        'exportPdf'          => 'exportPdf',
    ];

    // Menyimpan base64 chart images yang dikirim dari browser
    public string $barChartImage  = '';
    public string $lineChartImage = '';

    // Dipanggil dari JS sebelum export PDF
    public function receiveChartImages(string $bar, string $line): void
    {
        $this->barChartImage  = $bar;
        $this->lineChartImage = $line;
        $this->dispatch('charts-received');
    }

    public function exportExcel()
    {
        return Excel::download(
            new ReportsExport($this->year, $this->monthlyBreakdown, $this->categoryBreakdown),
            'laporan-keuangan-' . $this->year . '.xlsx'
        );
    }

    public function exportPdf(): mixed
    {
        $pdf = Pdf::loadView('exports.reports-pdf', [
            'year'        => $this->year,
            'summary'     => $this->yearlySummary,
            'monthlyData' => $this->monthlyBreakdown,
            'categories'  => $this->categoryBreakdown,
            'chartImages' => [
                'bar'  => $this->barChartImage  ?: null,
                'line' => $this->lineChartImage ?: null,
            ],
        ])->setPaper('a4', 'portrait');

        // Reset setelah dipakai
        $this->barChartImage  = '';
        $this->lineChartImage = '';

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-keuangan-' . $this->year . '.pdf'
        );
    }

    public function updatingYear(): void
    {
        // Will trigger re-render with fresh chartData
    }

    public function render()
    {
        return view('livewire.reports.index')
            ->layout('layouts.app', ['title' => 'Laporan Keuangan']);
    }
}
