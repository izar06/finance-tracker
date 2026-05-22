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
    public string $reportType = 'monthly'; // monthly | category | comparison

    public function mount(): void
    {
        $this->year = (string) now()->year;
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
        $rows = [];
        for ($m = 1; $m <= 12; $m++) {
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
        $income = Transaction::income()
            ->whereYear('date', $this->year)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        $expense = Transaction::expense()
            ->whereYear('date', $this->year)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        return compact('income', 'expense');
    }

    public function getYearlySummaryProperty(): array
    {
        $income  = (float) Transaction::income()->whereYear('date', $this->year)->sum('amount');
        $expense = (float) Transaction::expense()->whereYear('date', $this->year)->sum('amount');

        return [
            'income'       => $income,
            'expense'      => $expense,
            'balance'      => $income - $expense,
            'savings_rate' => $income > 0 ? round((($income - $expense) / $income) * 100, 1) : 0,
            'avg_monthly_income'  => round($income / 12),
            'avg_monthly_expense' => round($expense / 12),
            'total_assets'        => (float) Asset::sum('current_value'),
            'active_goals'        => FinancialGoal::active()->count(),
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
