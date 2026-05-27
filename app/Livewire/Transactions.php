<?php

namespace App\Livewire;

use App\Exports\TransactionsExport;
use App\Models\Category;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Transactions extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $filterType = '';
    public string $filterCategory = '';
    public string $filterPaymentMethod = '';
    public string $filterMonth = '';
    public string $filterYear = '';
    public string $sortBy = 'date';
    public string $sortDir = 'desc';

    // Form
    public bool $showForm             = false;
    public bool $showDeleteModal      = false;
    public bool $showBulkDeleteModal  = false;
    public ?int $editingId            = null;
    public ?int $deletingId           = null;

    // Bulk selection
    public array $selectedIds = [];
    public bool  $selectAll   = false;

    // Fields
    public string $type = 'expense';
    public string $title = '';
    public string $amount = '';
    public string $category = '';
    public string $payment_method = '';
    public string $date = '';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'type'           => 'required|in:income,expense',
            'title'          => 'required|string|max:255',
            'amount'         => ['required', function($a,$v,$f){ $n=str_replace(".","",$v); if(!is_numeric($n)||$n<1)$f("Jumlah harus berupa angka lebih dari 0."); }],
            'category'       => 'required|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'date'           => 'required|date',
            'notes'          => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'type'           => 'Tipe',
        'title'          => 'Judul',
        'amount'         => 'Jumlah',
        'category'       => 'Kategori',
        'payment_method' => 'Metode Pembayaran',
        'date'           => 'Tanggal',
        'notes'          => 'Catatan',
    ];

    public function updatingFilterPaymentMethod(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll   = false;
    }

    public function updatingFilterType(): void      { $this->resetPage(); $this->selectedIds = []; $this->selectAll = false; $this->filterCategory = ''; }
    public function updatingFilterCategory(): void  { $this->resetPage(); $this->selectedIds = []; $this->selectAll = false; }
    public function updatingFilterMonth(): void     { $this->resetPage(); $this->selectedIds = []; $this->selectAll = false; }
    public function updatingFilterYear(): void      { $this->resetPage(); $this->selectedIds = []; $this->selectAll = false; }

    // ── Bulk Selection ────────────────────────────────────────────────────
    public function toggleSelectAll(): void
    {
        if ($this->selectAll) {
            $ids = $this->getQuery()->paginate(15)->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $this->selectedIds = $ids;
        } else {
            $this->selectedIds = [];
        }
    }

    public function updatedSelectedIds(): void
    {
        $pageIds = $this->getQuery()->paginate(15)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selectAll = !empty($this->selectedIds)
            && count(array_intersect($this->selectedIds, $pageIds)) === count($pageIds);
    }

    public function confirmBulkDelete(): void
    {
        if (empty($this->selectedIds)) {
            $this->dispatch('notify', message: 'Pilih minimal 1 transaksi untuk dihapus.', type: 'error');
            return;
        }
        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete(): void
    {
        $ids = array_map('intval', $this->selectedIds);

        // Security: pastikan semua ID milik user yang login
        Transaction::whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->delete();

        $count = count($ids);
        $this->selectedIds        = [];
        $this->selectAll          = false;
        $this->showBulkDeleteModal = false;

        $this->dispatch('notify', message: "{$count} transaksi berhasil dihapus.", type: 'success');
    }

    public function updatedType(): void
    {
        $this->category = '';
        $this->resetValidation('category');
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editTransaction(int $id): void
    {
        $transaction = Transaction::findOrFail($id);
        abort_if($transaction->user_id !== auth()->id(), 403);
        $this->editingId = $id;
        $this->type = $transaction->type;
        $this->title = $transaction->title;
        $this->amount = (string) (int) $transaction->amount; // raw number, diformat JS di display input
        $this->category = $transaction->category;
        $this->payment_method = $transaction->payment_method ?? '';
        $this->date = $transaction->date->format('Y-m-d');
        $this->notes = $transaction->notes ?? '';
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function saveTransaction(): void
    {
        $this->validate();

        $data = [
            'type'           => $this->type,
            'title'          => $this->title,
            'amount'         => (float) str_replace(['.', ','], ['', '.'], $this->amount),
            'category'       => $this->category,
            'payment_method' => $this->payment_method ?: null,
            'date'           => $this->date,
            'notes'          => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Transaction::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Transaksi berhasil diperbarui.', type: 'success');
        } else {
            Transaction::create($data);
            $this->dispatch('notify', message: 'Transaksi berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteTransaction(): void
    {
        Transaction::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('notify', message: 'Transaksi berhasil dihapus.', type: 'success');
    }

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
            $this->sortBy = $column;
            $this->sortDir = 'desc';
        }
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->type = 'expense';
        $this->title = '';
        $this->amount = '';
        $this->category = '';
        $this->payment_method = '';
        $this->date = now()->format('Y-m-d');
        $this->notes = '';
        $this->resetValidation();
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

    private function getQuery()
    {
        return Transaction::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterPaymentMethod, fn($q) => $q->where('payment_method', $this->filterPaymentMethod))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('date', $this->filterMonth))
            ->when($this->filterYear, fn($q) => $q->whereYear('date', $this->filterYear))
            ->orderBy($this->sortBy, $this->sortDir);
    }

    public function getCategoriesProperty(): array
    {
        if ($this->filterType === 'income') {
            return Category::namesForType('income');
        }
        if ($this->filterType === 'expense') {
            return Category::namesForType('expense');
        }
        return array_merge(Category::namesForType('income'), Category::namesForType('expense'));
    }

    public function getFormCategoriesProperty(): array
    {
        return Category::namesForType($this->type);
    }

    public function render()
    {
        $transactions = $this->getQuery()->paginate(15);

        return view('livewire.transactions.index', [
            'transactions' => $transactions,
        ])->layout('layouts.app', ['title' => 'Transaksi']);
    }
}
