<?php

namespace App\Livewire;

use App\Exports\TransactionsExport;
use App\Imports\TransactionsImport;
use App\Models\Category;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Transactions extends Component
{
    use WithPagination, WithFileUploads;

    // Filters
    public string $search              = '';
    public string $filterType          = '';
    public string $filterCategory      = '';
    public string $filterPaymentMethod = '';
    public string $filterMonth         = '';
    public string $filterYear          = '';
    public string $filterRecurring     = '';
    public string $sortBy              = 'date';
    public string $sortDir             = 'desc';

    // Form
    public bool $showForm              = false;
    public bool $showDeleteModal       = false;
    public bool $showRecurringDetail   = false;
    public bool $showBulkDeleteModal   = false;
    public ?int $editingId             = null;
    public ?int $deletingId            = null;
    public ?int $recurringDetailId     = null;
    public string $deleteScope         = 'single'; // single | all

    // Bulk Delete
    public array $selectedIds          = [];
    public bool $selectAll             = false;

    // Import
    public bool $showImportModal       = false;
    public $importFile                 = null;
    public ?int $importedCount         = null;
    public ?int $skippedCount          = null;
    public array $importErrors         = [];

    // Fields — transaksi utama
    public string $type           = 'expense';
    public string $title          = '';
    public string $amount         = '';
    public string $category       = '';
    public string $payment_method = '';
    public string $date           = '';
    public string $notes          = '';

    // Fields — recurring
    public bool   $is_recurring        = false;
    public string $recurring_frequency = 'monthly';
    public string $recurring_ends_at   = '';

    protected function rules(): array
    {
        $rules = [
            'type'           => 'required|in:income,expense',
            'title'          => 'required|string|max:255',
            'amount'         => ['required', function ($a, $v, $f) {
                $n = str_replace('.', '', $v);
                if (!is_numeric($n) || $n < 1) $f('Jumlah harus berupa angka lebih dari 0.');
            }],
            'category'       => 'required|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'date'           => 'required|date',
            'notes'          => 'nullable|string|max:500',
            'is_recurring'   => 'boolean',
        ];

        if ($this->is_recurring) {
            $rules['recurring_frequency'] = 'required|in:daily,weekly,monthly,yearly';
            $rules['recurring_ends_at']   = 'nullable|date|after:date';
        }

        return $rules;
    }

    protected array $validationAttributes = [
        'type'                => 'Tipe',
        'title'               => 'Judul',
        'amount'              => 'Jumlah',
        'category'            => 'Kategori',
        'payment_method'      => 'Metode Pembayaran',
        'date'                => 'Tanggal',
        'notes'               => 'Catatan',
        'is_recurring'        => 'Transaksi Berulang',
        'recurring_frequency' => 'Frekuensi',
        'recurring_ends_at'   => 'Berakhir pada',
    ];

    public function updatingSearch(): void            { $this->resetPage(); }
    public function updatingFilterType(): void        { $this->resetPage(); $this->filterCategory = ''; }
    public function updatingFilterCategory(): void    { $this->resetPage(); }
    public function updatingFilterPaymentMethod(): void { $this->resetPage(); }
    public function updatingFilterMonth(): void       { $this->resetPage(); }
    public function updatingFilterYear(): void        { $this->resetPage(); }
    public function updatingFilterRecurring(): void   { $this->resetPage(); }
    public function updatedType(): void               { $this->category = ''; $this->resetValidation('category'); }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editTransaction(int $id): void
    {
        $tx = Transaction::findOrFail($id);
        $this->editingId          = $id;
        $this->type               = $tx->type;
        $this->title              = $tx->title;
        $this->amount             = (string) (int) $tx->amount;
        $this->category           = $tx->category;
        $this->payment_method     = $tx->payment_method ?? '';
        $this->date               = $tx->date->format('Y-m-d');
        $this->notes              = $tx->notes ?? '';
        $this->is_recurring       = $tx->is_recurring;
        $this->recurring_frequency = $tx->recurring_frequency ?? 'monthly';
        $this->recurring_ends_at  = $tx->recurring_ends_at?->format('Y-m-d') ?? '';
        $this->showForm           = true;
        $this->dispatch('currency:rebind');
    }

    public function saveTransaction(): void
    {
        $this->validate();

        $startDate = Carbon::parse($this->date);

        $data = [
            'type'                => $this->type,
            'title'               => $this->title,
            'amount'              => (float) str_replace(['.', ','], ['', '.'], $this->amount),
            'category'            => $this->category,
            'payment_method'      => $this->payment_method ?: null,
            'date'                => $this->date,
            'notes'               => $this->notes ?: null,
            'is_recurring'        => $this->is_recurring,
            'recurring_frequency' => $this->is_recurring ? $this->recurring_frequency : null,
            'recurring_ends_at'   => ($this->is_recurring && $this->recurring_ends_at) ? $this->recurring_ends_at : null,
            'next_recurring_date' => null,
            'recurring_parent_id' => null,
        ];

        // Hitung next_recurring_date dari tanggal awal
        if ($this->is_recurring) {
            $data['next_recurring_date'] = match ($this->recurring_frequency) {
                'daily'   => $startDate->copy()->addDay(),
                'weekly'  => $startDate->copy()->addWeek(),
                'monthly' => $startDate->copy()->addMonth(),
                'yearly'  => $startDate->copy()->addYear(),
            };
        }

        if ($this->editingId) {
            Transaction::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Transaksi berhasil diperbarui.', type: 'success');
        } else {
            Transaction::create($data);
            $this->dispatch('notify', message: 'Transaksi berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    // ── Delete ──────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->deleteScope     = 'single';
        $this->showDeleteModal = true;
    }

    public function deleteTransaction(): void
    {
        $tx = Transaction::findOrFail($this->deletingId);

        if ($this->deleteScope === 'all') {
            // Hapus template dan semua anaknya
            $parentId = $tx->recurring_parent_id ?? $tx->id;

            Transaction::withoutGlobalScopes()
                ->where(function ($q) use ($parentId) {
                    $q->where('id', $parentId)
                      ->orWhere('recurring_parent_id', $parentId);
                })
                ->where('user_id', auth()->id())
                ->delete();

            $this->dispatch('notify', message: 'Semua transaksi berulang berhasil dihapus.', type: 'success');
        } else {
            $tx->delete();
            $this->dispatch('notify', message: 'Transaksi berhasil dihapus.', type: 'success');
        }

        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->deleteScope     = 'single';
    }

    // ── Bulk Delete ─────────────────────────────────────────────────────────

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->getQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function confirmBulkDelete(): void
    {
        if (count($this->selectedIds) > 0) {
            $this->showBulkDeleteModal = true;
        }
    }

    public function bulkDeleteTransactions(): void
    {
        Transaction::whereIn('id', $this->selectedIds)
            ->where('user_id', auth()->id())
            ->delete();

        $count = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll   = false;
        $this->showBulkDeleteModal = false;

        $this->dispatch('notify', message: "{$count} transaksi berhasil dihapus.", type: 'success');
    }

    // ── Recurring detail modal ───────────────────────────────────────────────

    public function showRecurringInfo(int $id): void
    {
        $this->recurringDetailId   = $id;
        $this->showRecurringDetail = true;
    }

    public function getRecurringDetailProperty(): ?Transaction
    {
        if (!$this->recurringDetailId) return null;
        return Transaction::find($this->recurringDetailId);
    }

    // ── Stop / resume recurring ──────────────────────────────────────────────

    public function stopRecurring(int $id): void
    {
        $tx = Transaction::findOrFail($id);
        $tx->update([
            'is_recurring'        => false,
            'next_recurring_date' => null,
        ]);
        $this->showRecurringDetail = false;
        $this->dispatch('notify', message: 'Transaksi berulang dihentikan.', type: 'success');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function sortColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'desc';
        }
    }

    private function resetForm(): void
    {
        $this->editingId           = null;
        $this->type                = 'expense';
        $this->title               = '';
        $this->amount              = '';
        $this->category            = '';
        $this->payment_method      = '';
        $this->date                = now()->format('Y-m-d');
        $this->notes               = '';
        $this->is_recurring        = false;
        $this->recurring_frequency = 'monthly';
        $this->recurring_ends_at   = '';
        $this->resetValidation();
    }

    private function getQuery()
    {
        return Transaction::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterPaymentMethod, fn($q) => $q->where('payment_method', $this->filterPaymentMethod))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('date', $this->filterMonth))
            ->when($this->filterYear, fn($q) => $q->whereYear('date', $this->filterYear))
            ->when($this->filterRecurring === 'recurring', fn($q) => $q->where('is_recurring', true))
            ->when($this->filterRecurring === 'not_recurring', fn($q) => $q->where('is_recurring', false)->whereNull('recurring_parent_id'))
            ->orderBy($this->sortBy, $this->sortDir);
    }

    public function getCategoriesProperty(): array
    {
        if ($this->filterType === 'income')  return Category::namesForType('income');
        if ($this->filterType === 'expense') return Category::namesForType('expense');
        return array_merge(Category::namesForType('income'), Category::namesForType('expense'));
    }

    public function getFormCategoriesProperty(): array
    {
        return Category::namesForType($this->type);
    }

    public function exportExcel()
    {
        return Excel::download(
            new TransactionsExport($this->getQuery()),
            'transaksi-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $transactions = $this->getQuery()->get();
        $pdf = Pdf::loadView('exports.transactions-pdf', [
            'transactions' => $transactions,
            'totalIncome'  => $transactions->where('type', 'income')->sum('amount'),
            'totalExpense' => $transactions->where('type', 'expense')->sum('amount'),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'transaksi-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    // ── Import ──────────────────────────────────────────────────────────────

    public function openImportModal(): void
    {
        $this->importFile     = null;
        $this->importedCount  = null;
        $this->skippedCount   = null;
        $this->importErrors   = [];
        $this->showImportModal = true;
    }

    public function importTransactions(): void
    {
        $this->validate(['importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048']);

        $import = new TransactionsImport();
        Excel::import($import, $this->importFile->getRealPath());

        $this->importedCount = $import->imported;
        $this->skippedCount  = $import->skipped;
        $this->importErrors  = array_slice($import->errors, 0, 10);
        $this->importFile    = null;

        if ($import->imported > 0) {
            $this->dispatch('notify', message: "{$import->imported} transaksi berhasil diimport.", type: 'success');
        }
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->importFile      = null;
        $this->importedCount   = null;
        $this->skippedCount    = null;
        $this->importErrors    = [];
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['tipe', 'judul', 'jumlah', 'kategori', 'tanggal', 'metode_pembayaran', 'catatan']);
            fputcsv($out, ['income',  'Gaji Bulanan', '5000000', 'Gaji',              date('Y-m-d'), 'Transfer Bank', 'Contoh pemasukan']);
            fputcsv($out, ['expense', 'Makan Siang',  '50000',   'Makanan & Minuman', date('Y-m-d'), 'Tunai',         'Contoh pengeluaran']);
            fclose($out);
        }, 'template-import-transaksi.csv', $headers);
    }

    public function getFilteredSummaryProperty(): array
    {
        $result = $this->getQuery()
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                COUNT(*) as total_count
            ")
            ->first();

        $income  = (float) ($result->total_income  ?? 0);
        $expense = (float) ($result->total_expense ?? 0);

        return [
            'income'  => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'count'   => (int) ($result->total_count ?? 0),
        ];
    }

    public function render()
    {
        $transactions = $this->getQuery()->paginate(15);

        return view('livewire.transactions.index', [
            'transactions'    => $transactions,
            'filteredSummary' => $this->filteredSummary,
        ])->layout('layouts.app', ['title' => 'Transaksi']);
    }
}