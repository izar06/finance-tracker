<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Bill;
use App\Models\Budget;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public string $selectedYear;
    public string $selectedMonth;

    public function mount(): void
    {
        $this->selectedYear = (string) now()->year;
        $this->selectedMonth = (string) now()->month;
    }

    public function getMonthlyIncomeProperty(): float
    {
        return Transaction::income()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->sum('amount');
    }

    public function getMonthlyExpenseProperty(): float
    {
        return Transaction::expense()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->sum('amount');
    }

    public function getMonthlyBalanceProperty(): float
    {
        return $this->monthlyIncome - $this->monthlyExpense;
    }

    public function getTotalAssetsProperty(): float
    {
        return Asset::sum('current_value');
    }

    public function getActiveGoalsProperty()
    {
        return FinancialGoal::active()->orderBy('deadline')->take(3)->get();
    }

    public function getUpcomingBillsProperty()
    {
        return Bill::active()
            ->get()
            ->filter(fn($b) => !$b->is_paid_this_month && $b->days_until_due <= 7)
            ->sortBy('days_until_due')
            ->take(5)
            ->values();
    }

    // public function getUpcomingBillsProperty()
    // {
    //     return Bill::active()
    //         ->get()
    //         ->filter(fn($b) => !$b->is_paid_this_month && $b->days_until_due <= 7)
    //         ->sortBy('days_until_due')
    //         ->take(5)
    //         ->values();
    // }

    public function getBudgetSummaryProperty()
    {
        return Budget::where('month', now()->month)
            ->where('year', now()->year)
            ->orderBy('category')
            ->take(4)
            ->get();
    }

    public function getRecentTransactionsProperty()
    {
        return Transaction::orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function getMonthlyChartDataProperty(): array
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $incomeData[] = (float) Transaction::income()
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');

            $expenseData[] = (float) Transaction::expense()
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    public function getPaymentMethodChartDataProperty(): array
    {
        // All-time saldo per metode (tidak dibatasi bulan)
        $allExpense = Transaction::expense()
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()->keyBy('payment_method');

        $allIncome = Transaction::income()
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()->keyBy('payment_method');

        // Bulan ini (untuk progress bar & badge)
        $monthExpense = Transaction::expense()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()->keyBy('payment_method');

        $monthIncome = Transaction::income()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()->keyBy('payment_method');

        $allMethods = $allExpense->keys()->merge($allIncome->keys())->unique()->values();

        $result = [];
        foreach ($allMethods as $method) {
            // 5 transaksi terbaru untuk metode ini
            $recentTx = Transaction::whereNotNull('payment_method')
                ->where('payment_method', $method)
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id','type','title','amount','category','date'])
                ->map(fn($t) => [
                    'type'     => $t->type,
                    'title'    => $t->title,
                    'amount'   => (float) $t->amount,
                    'category' => $t->category,
                    'date'     => $t->date->format('d M Y'),
                ])->toArray();

            $totalIncome  = (float) ($allIncome[$method]->total  ?? 0);
            $totalExpense = (float) ($allExpense[$method]->total ?? 0);

            $result[] = [
                'method'              => $method,
                'icon'                => Transaction::$paymentMethods[$method] ?? '🔖',
                // All-time
                'balance'             => $totalIncome - $totalExpense,
                'all_income'          => $totalIncome,
                'all_expense'         => $totalExpense,
                'all_income_count'    => (int) ($allIncome[$method]->count  ?? 0),
                'all_expense_count'   => (int) ($allExpense[$method]->count ?? 0),
                // Bulan ini
                'expense'             => (float) ($monthExpense[$method]->total ?? 0),
                'income'              => (float) ($monthIncome[$method]->total  ?? 0),
                'expense_count'       => (int) ($monthExpense[$method]->count ?? 0),
                'income_count'        => (int) ($monthIncome[$method]->count  ?? 0),
                // History
                'recent_transactions' => $recentTx,
            ];
        }

        usort($result, fn($a, $b) => ($b['all_income'] + $b['all_expense']) <=> ($a['all_income'] + $a['all_expense']));

        return $result;
    }

    public function getCategoryExpenseChartDataProperty(): array
    {
        $data = Transaction::expense()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('category')->toArray(),
            'data'   => $data->pluck('total')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.index')->layout('layouts.app', [
            'title' => 'Dashboard',
        ]);
    }
}
