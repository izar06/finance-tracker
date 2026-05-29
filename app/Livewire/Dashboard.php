<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Bill;
use App\Models\Budget;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $selectedYear;
    public string $selectedMonth;

    public function mount(): void
    {
        $this->selectedYear  = (string) now()->year;
        $this->selectedMonth = (string) now()->month;
    }

    // ── Summary cards ────────────────────────────────────────────────────

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

    // ── Widgets ──────────────────────────────────────────────────────────

    public function getActiveGoalsProperty()
    {
        return FinancialGoal::active()->orderBy('deadline')->take(3)->get();
    }

    // ── Top 5 pengeluaran terbesar bulan ini ─────────────────────────────
    public function getTopExpensesProperty()
    {
        return Transaction::expense()
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->orderByDesc('amount')
            ->take(5)
            ->get(['id', 'title', 'amount', 'category', 'date']);
    }

    // ── Goals paling dekat deadline (max 3, hanya yang aktif) ────────────
    public function getUrgentGoalsProperty()
    {
        return FinancialGoal::active()
            ->whereNotNull('deadline')
            ->orderBy('deadline')
            ->take(3)
            ->get();
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

    // ── Chart: income vs expense 12 bulan ────────────────────────────────
    //
    // SEBELUM: 24 query (2 query × 12 bulan dalam loop)
    // SESUDAH: 2 query — GROUP BY year, month sekaligus

    public function getMonthlyChartDataProperty(): array
    {
        $userId = auth()->id();

        // Satu query untuk income, satu untuk expense — keduanya GROUP BY bulan
        $rawIncome = Transaction::income()
            ->whereRaw('(year(date) > ? OR (year(date) = ? AND month(date) >= ?))', [
                now()->subMonths(11)->year,
                now()->subMonths(11)->year,
                now()->subMonths(11)->month,
            ])
            ->selectRaw('YEAR(date) as yr, MONTH(date) as mo, SUM(amount) as total')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->get()
            ->mapWithKeys(fn($row) => [
                $row->yr . '-' . str_pad($row->mo, 2, '0', STR_PAD_LEFT) => $row->total
            ]);

        $rawExpense = Transaction::expense()
            ->whereRaw('(year(date) > ? OR (year(date) = ? AND month(date) >= ?))', [
                now()->subMonths(11)->year,
                now()->subMonths(11)->year,
                now()->subMonths(11)->month,
            ])
            ->selectRaw('YEAR(date) as yr, MONTH(date) as mo, SUM(amount) as total')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->get()
            ->mapWithKeys(fn($row) => [
                $row->yr . '-' . str_pad($row->mo, 2, '0', STR_PAD_LEFT) => $row->total
            ]);

        $months     = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $key    = $date->format('Y-m');
            $months[]     = $date->translatedFormat('M Y');
            $incomeData[]  = (float) ($rawIncome[$key]  ?? 0);
            $expenseData[] = (float) ($rawExpense[$key] ?? 0);
        }

        return [
            'labels'  => $months,
            'income'  => $incomeData,
            'expense' => $expenseData,
        ];
    }

    // ── Chart: payment method breakdown ──────────────────────────────────
    //
    // SEBELUM: 4 query aggregate + N query di dalam loop (1 per metode)
    //          → total 4 + N query (makin banyak metode = makin lambat)
    //
    // SESUDAH: 5 query flat, tidak ada loop query sama sekali
    //   1. all-time expense per metode
    //   2. all-time income per metode
    //   3. bulan ini expense per metode
    //   4. bulan ini income per metode
    //   5. 5 transaksi terbaru per metode (satu query dengan ROW_NUMBER / subquery)

    public function getPaymentMethodChartDataProperty(): array
    {
        // ① All-time aggregate
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

        // ② Bulan ini aggregate
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

        // ③ 5 transaksi terbaru per metode — SATU query dengan subquery ranking
        //    Menggunakan user_id dari global scope yang sudah diterapkan
        $userId = auth()->id();

        $recentRaw = DB::select("
            SELECT t.*
            FROM transactions t
            INNER JOIN (
                SELECT id,
                       ROW_NUMBER() OVER (
                           PARTITION BY payment_method
                           ORDER BY date DESC, created_at DESC
                       ) AS rn
                FROM transactions
                WHERE payment_method IS NOT NULL
                  AND user_id = ?
            ) ranked ON t.id = ranked.id AND ranked.rn <= 5
            WHERE t.user_id = ?
        ", [$userId, $userId]);

        // Kelompokkan hasil query ke dalam map [payment_method => [tx, tx, ...]]
        $recentByMethod = collect($recentRaw)->groupBy('payment_method')
            ->map(fn($txs) => $txs->map(fn($t) => [
                'type'     => $t->type,
                'title'    => $t->title,
                'amount'   => (float) $t->amount,
                'category' => $t->category,
                'date'     => \Carbon\Carbon::parse($t->date)->format('d M Y'),
            ])->values()->toArray());

        // ④ Rakit hasil akhir — murni PHP, nol query tambahan
        $allMethods = $allExpense->keys()->merge($allIncome->keys())->unique()->values();

        $result = [];
        foreach ($allMethods as $method) {
            $totalIncome  = (float) ($allIncome[$method]->total  ?? 0);
            $totalExpense = (float) ($allExpense[$method]->total ?? 0);

            $result[] = [
                'method'              => $method,
                'icon'                => Transaction::$paymentMethods[$method] ?? '🔖',
                'balance'             => $totalIncome - $totalExpense,
                'all_income'          => $totalIncome,
                'all_expense'         => $totalExpense,
                'all_income_count'    => (int) ($allIncome[$method]->count  ?? 0),
                'all_expense_count'   => (int) ($allExpense[$method]->count ?? 0),
                'expense'             => (float) ($monthExpense[$method]->total ?? 0),
                'income'              => (float) ($monthIncome[$method]->total  ?? 0),
                'expense_count'       => (int) ($monthExpense[$method]->count ?? 0),
                'income_count'        => (int) ($monthIncome[$method]->count  ?? 0),
                'recent_transactions' => $recentByMethod->get($method, []),
            ];
        }

        usort($result, fn($a, $b) =>
            ($b['all_income'] + $b['all_expense']) <=> ($a['all_income'] + $a['all_expense'])
        );

        return $result;
    }

    // ── Chart: pengeluaran per kategori ─────────────────────────────────

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