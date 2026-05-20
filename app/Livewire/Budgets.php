<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\Component;

class Budgets extends Component
{
    // Filter bulan & tahun
    public int $month;
    public int $year;

    // Form
    public bool $showForm       = false;
    public bool $showDeleteModal = false;
    public ?int $editingId      = null;
    public ?int $deletingId     = null;

    // Fields
    public string $category = '';
    public string $amount   = '';
    public string $notes    = '';

    public function mount(): void
    {
        $this->month = now()->month;
        $this->year  = now()->year;
    }

    protected function rules(): array
    {
        $uniqueRule = \Illuminate\Validation\Rule::unique('budgets', 'category')
            ->where('user_id', auth()->id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->ignore($this->editingId);

        return [
            'category' => ['required', 'string', 'max:100', $uniqueRule],
            'amount'   => ['required', function ($attr, $val, $fail) {
                $n = (int) str_replace('.', '', $val);
                if (!is_numeric($n) || $n < 1) $fail('Jumlah anggaran harus lebih dari 0.');
            }],
            'notes'    => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'category' => 'Kategori',
        'amount'   => 'Jumlah Anggaran',
        'notes'    => 'Catatan',
    ];

    // ── Computed: daftar budget bulan/tahun terpilih ─────────────────────
    public function getBudgetsProperty()
    {
        return Budget::where('month', $this->month)
            ->where('year', $this->year)
            ->orderBy('category')
            ->get();
    }

    // ── Computed: kategori yang belum punya budget di bulan ini ──────────
    public function getAvailableCategoriesProperty(): array
    {
        $used = Budget::where('month', $this->month)
            ->where('year', $this->year)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->pluck('category')
            ->toArray();

        return array_values(
            array_filter(
                Category::namesForType('expense'),
                fn($cat) => !in_array($cat, $used)
            )
        );
    }

    // ── Computed: ringkasan bulan ini ─────────────────────────────────────
    public function getSummaryProperty(): array
    {
        $budgets = $this->budgets;

        $totalBudget = $budgets->sum('amount');
        $totalSpent  = $budgets->sum('spent');
        $onTrack     = $budgets->filter(fn($b) => $b->percentage < 80)->count();
        $warning     = $budgets->filter(fn($b) => $b->percentage >= 80 && $b->percentage < 100)->count();
        $overBudget  = $budgets->filter(fn($b) => $b->is_over_budget)->count();

        return compact('totalBudget', 'totalSpent', 'onTrack', 'warning', 'overBudget');
    }

    // ── Computed: total pengeluaran aktual bulan ini (termasuk yg tidak ada budgetnya) ──
    public function getTotalExpenseProperty(): float
    {
        return (float) Transaction::expense()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');
    }

    // ── Computed: pengeluaran kategori TANPA budget ───────────────────────
    public function getUnbudgetedExpensesProperty()
    {
        $budgetedCategories = Budget::where('month', $this->month)
            ->where('year', $this->year)
            ->pluck('category')
            ->toArray();

        return Transaction::expense()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->whereNotIn('category', $budgetedCategories)
            ->orderByDesc('total')
            ->get();
    }

    // ── Available years for filter ────────────────────────────────────────
    public function getAvailableYearsProperty(): array
    {
        $years = [];
        for ($y = now()->year + 1; $y >= now()->year - 2; $y--) {
            $years[] = $y;
        }
        return $years;
    }

    // ── Month navigation ──────────────────────────────────────────────────
    public function prevMonth(): void
    {
        if ($this->month === 1) {
            $this->month = 12;
            $this->year--;
        } else {
            $this->month--;
        }
    }

    public function nextMonth(): void
    {
        if ($this->month === 12) {
            $this->month = 1;
            $this->year++;
        } else {
            $this->month++;
        }
    }

    // ── Form CRUD ─────────────────────────────────────────────────────────
    public function openFormWithCategory(string $category): void
    {
        $this->resetForm();
        $this->category = $category;
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editBudget(int $id): void
    {
        $budget = Budget::findOrFail($id);
        $this->editingId = $id;
        $this->category  = $budget->category;
        $this->amount    = (string) (int) $budget->amount;
        $this->notes     = $budget->notes ?? '';
        $this->showForm  = true;
        $this->dispatch('currency:rebind');
    }

    public function saveBudget(): void
    {
        $this->validate();

        $data = [
            'category' => $this->category,
            'amount'   => (float) str_replace('.', '', $this->amount),
            'month'    => $this->month,
            'year'     => $this->year,
            'notes'    => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Budget::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Anggaran berhasil diperbarui.', type: 'success');
        } else {
            Budget::create($data);
            $this->dispatch('notify', message: 'Anggaran berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId     = $id;
        $this->showDeleteModal = true;
    }

    public function deleteBudget(): void
    {
        Budget::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->dispatch('notify', message: 'Anggaran berhasil dihapus.', type: 'success');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->category  = '';
        $this->amount    = '';
        $this->notes     = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.budgets.index')->layout('layouts.app', [
            'title' => 'Anggaran',
        ]);
    }
}
