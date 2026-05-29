<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class Budgets extends Component
{
    // Filter bulan & tahun
    public int $month;
    public int $year;

    // Form
    public bool $showForm          = false;
    public bool $showDeleteModal   = false;
    public bool $showCopyModal     = false;
    public bool $showBulkDeleteModal = false;
    public ?int $editingId         = null;
    public ?int $deletingId        = null;

    // Bulk select
    public array $selectedIds = [];
    public bool  $selectAll   = false;

    // Fields
    public string $category = '';
    public string $amount   = '';
    public string $notes    = '';

    // ── BARU: Inline category creation ───────────────────────────────────
    public bool   $showNewCategory    = false;
    public string $newCategoryName    = '';
    public string $newCategoryIcon    = '🏷️';
    public bool   $showIconPicker     = false;

    public array $iconOptions = [
        '🏷️','💰','💵','💳','🏦','📊','📈','📉',
        '💼','💻','🏢','🎁','🎀','🤝','📝',
        '🍽️','🍔','🍜','☕','🛒','🥗','🍕',
        '🚗','🚌','✈️','⛽','🚂','🛵','🚕',
        '🛍️','👔','👗','👠','🎽','💄','🧴',
        '🏠','⚡','💧','📱','🌐','🔧','🪴',
        '🏥','💊','🏋️','🧘','🦷','👓','🩺',
        '📚','✏️','🎓','📐','🔬','🖥️','📖',
        '🎮','🎬','🎵','🎨','📸','🎭','🎲',
        '🐷','🎯','⭐','🔑','🧾','📦','🗂️',
    ];

    // Copy modal
    public int    $copyFromMonth;
    public int    $copyFromYear;
    public bool   $copyOverwrite  = false;
    public array  $copyPreview    = [];

    public function mount(): void
    {
        $this->month         = now()->month;
        $this->year          = now()->year;
        $this->copyFromMonth = now()->subMonth()->month;
        $this->copyFromYear  = now()->subMonth()->year;
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

    // ── Computed ──────────────────────────────────────────────────────────

    public function getBudgetsProperty()
    {
        return Budget::where('month', $this->month)
            ->where('year', $this->year)
            ->orderBy('category')
            ->get();
    }

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

    public function getTotalExpenseProperty(): float
    {
        return (float) Transaction::expense()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');
    }

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

    public function getAvailableYearsProperty(): array
    {
        $years = [];
        for ($y = now()->year + 1; $y >= now()->year - 2; $y--) {
            $years[] = $y;
        }
        return $years;
    }

    public function getCopySourceLabelProperty(): string
    {
        return Carbon::create($this->copyFromYear, $this->copyFromMonth)->translatedFormat('F Y');
    }

    // ── Month navigation ──────────────────────────────────────────────────

    public function prevMonth(): void
    {
        if ($this->month === 1) { $this->month = 12; $this->year--; }
        else { $this->month--; }
    }

    public function nextMonth(): void
    {
        if ($this->month === 12) { $this->month = 1; $this->year++; }
        else { $this->month++; }
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
        abort_if($budget->user_id !== auth()->id(), 403);
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
        $this->editingId          = null;
        $this->category           = '';
        $this->amount             = '';
        $this->notes              = '';
        $this->showNewCategory    = false;
        $this->newCategoryName    = '';
        $this->newCategoryIcon    = '🏷️';
        $this->showIconPicker     = false;
        $this->resetValidation();
    }

    // ── BARU: Inline category creation ───────────────────────────────────

    public function toggleNewCategory(): void
    {
        $this->showNewCategory = !$this->showNewCategory;
        $this->newCategoryName = '';
        $this->newCategoryIcon = '🏷️';
        $this->showIconPicker  = false;
        $this->resetValidation('newCategoryName');
    }

    public function selectIcon(string $icon): void
    {
        $this->newCategoryIcon = $icon;
        $this->showIconPicker  = false;
    }

    public function createAndSelectCategory(): void
    {
        $this->validate([
            'newCategoryName' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('categories', 'name')
                    ->where('user_id', auth()->id())
                    ->where('type', 'expense'),
            ],
        ], [
            'newCategoryName.required' => 'Nama kategori tidak boleh kosong.',
            'newCategoryName.unique'   => 'Kategori dengan nama ini sudah ada.',
            'newCategoryName.max'      => 'Nama kategori maksimal 50 karakter.',
        ]);

        $cat = Category::create([
            'name'       => trim($this->newCategoryName),
            'icon'       => $this->newCategoryIcon,
            'type'       => 'expense',
            'is_default' => false,
        ]);

        // Langsung pilih kategori yang baru dibuat
        $this->category        = $cat->name;
        $this->showNewCategory = false;
        $this->newCategoryName = '';
        $this->newCategoryIcon = '🏷️';
        $this->showIconPicker  = false;

        $this->dispatch('notify', message: "Kategori \"{$cat->name}\" berhasil dibuat dan dipilih.", type: 'success');
    }

    // ── Salin Anggaran ────────────────────────────────────────────────────

    public function openCopyModal(): void
    {
        $prev = Carbon::create($this->year, $this->month)->subMonth();
        $this->copyFromMonth = $prev->month;
        $this->copyFromYear  = $prev->year;
        $this->copyOverwrite = false;
        $this->loadCopyPreview();
        $this->showCopyModal = true;
    }

    public function updatedCopyFromMonth(): void { $this->loadCopyPreview(); }
    public function updatedCopyFromYear(): void  { $this->loadCopyPreview(); }
    public function updatedCopyOverwrite(): void { $this->loadCopyPreview(); }

    private function loadCopyPreview(): void
    {
        $source = Budget::where('month', $this->copyFromMonth)
            ->where('year', $this->copyFromYear)
            ->orderBy('category')
            ->get();

        $existing = Budget::where('month', $this->month)
            ->where('year', $this->year)
            ->pluck('amount', 'category');

        $this->copyPreview = $source->map(function ($b) use ($existing) {
            $alreadyExists = $existing->has($b->category);
            return [
                'category'      => $b->category,
                'amount'        => (float) $b->amount,
                'already_exists'=> $alreadyExists,
                'will_copy'     => !$alreadyExists || $this->copyOverwrite,
            ];
        })->toArray();
    }

    public function copyBudgets(): void
    {
        if (empty($this->copyPreview)) {
            $this->dispatch('notify', message: 'Tidak ada anggaran untuk disalin.', type: 'error');
            return;
        }

        $source = Budget::where('month', $this->copyFromMonth)
            ->where('year', $this->copyFromYear)
            ->get();

        if ($source->isEmpty()) {
            $this->dispatch('notify', message: 'Tidak ada anggaran di bulan sumber.', type: 'error');
            return;
        }

        $copied  = 0;
        $skipped = 0;
        $updated = 0;

        foreach ($source as $budget) {
            $existing = Budget::where('month', $this->month)
                ->where('year', $this->year)
                ->where('category', $budget->category)
                ->first();

            if ($existing) {
                if ($this->copyOverwrite) {
                    $existing->update([
                        'amount' => $budget->amount,
                        'notes'  => $budget->notes,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                Budget::create([
                    'category' => $budget->category,
                    'amount'   => $budget->amount,
                    'month'    => $this->month,
                    'year'     => $this->year,
                    'notes'    => $budget->notes,
                ]);
                $copied++;
            }
        }

        $this->showCopyModal = false;
        $this->copyPreview   = [];

        $parts = [];
        if ($copied  > 0) $parts[] = "{$copied} anggaran disalin";
        if ($updated > 0) $parts[] = "{$updated} diperbarui";
        if ($skipped > 0) $parts[] = "{$skipped} dilewati";

        $this->dispatch('notify',
            message: implode(', ', $parts) . '.',
            type: 'success'
        );
    }

    public function closeCopyModal(): void
    {
        $this->showCopyModal = false;
        $this->copyPreview   = [];
    }

    // ── Bulk select & delete ─────────────────────────────────────────────
    public function updatedSelectAll(bool $value): void
    {
        $this->selectedIds = $value
            ? $this->budgets->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function updatedSelectedIds(): void
    {
        $this->selectAll = count($this->selectedIds) === $this->budgets->count() && $this->budgets->count() > 0;
    }

    public function openBulkDeleteModal(): void
    {
        if (empty($this->selectedIds)) return;
        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete(): void
    {
        $ids = array_map('intval', $this->selectedIds);

        Budget::whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->delete();

        $count = count($ids);
        $this->selectedIds         = [];
        $this->selectAll           = false;
        $this->showBulkDeleteModal = false;

        $this->dispatch('notify', message: "{$count} anggaran berhasil dihapus.", type: 'success');
    }

    public function cancelBulkDelete(): void
    {
        $this->showBulkDeleteModal = false;
    }

    // ── Render ────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.budgets.index')->layout('layouts.app', [
            'title' => 'Anggaran',
        ]);
    }
}