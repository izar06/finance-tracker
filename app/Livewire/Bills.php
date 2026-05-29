<?php

namespace App\Livewire;

use App\Models\Bill;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Livewire\Component;

class Bills extends Component
{
    public string $filterStatus = 'active';

    // Form
    public bool  $showForm            = false;
    public bool  $showDeleteModal     = false;
    public bool  $showPayModal        = false;
    public bool  $showIconPicker      = false;
    public bool  $showBulkDeleteModal = false;
    public ?int  $editingId           = null;
    public ?int  $deletingId          = null;
    public ?int  $payingId            = null;

    // Bulk select
    public array $selectedIds  = [];
    public bool  $selectAll    = false;

    // Fields
    public string $name           = '';
    public string $category       = '';
    public string $amount         = '';
    public int    $due_day        = 1;
    public string $frequency      = 'monthly';
    public string $status         = 'active';
    public string $payment_method = '';
    public string $icon           = '📄';
    public string $notes          = '';

    // Pay modal
    public bool   $autoCreateTransaction = true;
    public string $payDate = '';
    public string $payNotes = '';

    protected function rules(): array
    {
        return [
            'name'           => 'required|string|max:100',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:1',
            'due_day'        => 'required|integer|min:1|max:31',
            'frequency'      => 'required|in:monthly,quarterly,yearly',
            'status'         => 'required|in:active,paused,cancelled',
            'payment_method' => 'nullable|string|max:50',
            'icon'           => 'required|string|max:10',
            'notes'          => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'name'     => 'Nama Tagihan',
        'category' => 'Kategori',
        'amount'   => 'Nominal',
        'due_day'  => 'Tanggal Jatuh Tempo',
    ];

    public function mount(): void
    {
        $this->payDate = now()->format('Y-m-d');
    }

    // ── Computed ─────────────────────────────────────────────────────────
    public function getBillsProperty()
    {
        return Bill::when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderBy('due_day')
            ->get();
    }

    public function getSummaryProperty(): array
    {
        $active  = Bill::active()->get();
        $monthly = $active->sum(fn($b) => match($b->frequency) {
            'monthly'   => (float) $b->amount,
            'quarterly' => (float) $b->amount / 3,
            'yearly'    => (float) $b->amount / 12,
            default     => 0,
        });

        $unpaidCount  = $active->filter(fn($b) => !$b->is_paid_this_month)->count();
        $overdueCount = $active->filter(fn($b) => $b->urgency === 'overdue' && !$b->is_paid_this_month)->count();
        $urgentCount  = $active->filter(fn($b) => in_array($b->urgency, ['urgent', 'soon']) && !$b->is_paid_this_month)->count();

        return [
            'total_active'    => $active->count(),
            'monthly_estimate'=> $monthly,
            'yearly_estimate' => $monthly * 12,
            'unpaid_count'    => $unpaidCount,
            'overdue_count'   => $overdueCount,
            'urgent_count'    => $urgentCount,
        ];
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::orderBy('name')->get();
    }

    // ── Filter ───────────────────────────────────────────────────────────
    public function setFilter(string $status): void
    {
        $this->filterStatus = $status;
    }

    // ── CRUD ─────────────────────────────────────────────────────────────
    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editBill(int $id): void
    {
        $bill = Bill::findOrFail($id);
        abort_if($bill->user_id !== auth()->id(), 403);

        $this->editingId      = $id;
        $this->name           = $bill->name;
        $this->category       = $bill->category;
        $this->amount         = (string)(int)$bill->amount;
        $this->due_day        = $bill->due_day;
        $this->frequency      = $bill->frequency;
        $this->status         = $bill->status;
        $this->payment_method = $bill->payment_method ?? '';
        $this->icon           = $bill->icon;
        $this->notes          = $bill->notes ?? '';
        $this->showForm       = true;
        $this->dispatch('currency:rebind');
    }

    public function saveBill(): void
    {
        // Normalize amount: strip dot separators sebelum validasi
        $this->amount = str_replace('.', '', $this->amount);

        $this->validate();

        $data = [
            'name'           => $this->name,
            'category'       => $this->category,
            'amount'         => (float) str_replace('.', '', $this->amount),
            'due_day'        => $this->due_day,
            'frequency'      => $this->frequency,
            'status'         => $this->status,
            'payment_method' => $this->payment_method ?: null,
            'icon'           => $this->icon,
            'notes'          => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Bill::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Tagihan berhasil diperbarui.', type: 'success');
        } else {
            Bill::create($data);
            $this->dispatch('notify', message: 'Tagihan berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $bill = Bill::findOrFail($id);
        abort_if($bill->user_id !== auth()->id(), 403);
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function deleteBill(): void
    {
        Bill::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->dispatch('notify', message: 'Tagihan berhasil dihapus.', type: 'success');
    }

    // ── Mark as Paid ─────────────────────────────────────────────────────
    public function openPayModal(int $id): void
    {
        $bill = Bill::findOrFail($id);
        abort_if($bill->user_id !== auth()->id(), 403);
        $this->payingId   = $id;
        $this->payDate    = now()->format('Y-m-d');
        $this->payNotes   = '';
        $this->autoCreateTransaction = true;
        $this->showPayModal = true;
    }

    public function markAsPaid(): void
    {
        $bill = Bill::findOrFail($this->payingId);
        abort_if($bill->user_id !== auth()->id(), 403);

        // Update last_paid_at
        $bill->update(['last_paid_at' => $this->payDate]);

        // Auto buat transaksi pengeluaran
        if ($this->autoCreateTransaction) {
            Transaction::create([
                'type'           => 'expense',
                'title'          => 'Tagihan: ' . $bill->name,
                'amount'         => $bill->amount,
                'category'       => $bill->category,
                'payment_method' => $bill->payment_method,
                'date'           => $this->payDate,
                'notes'          => $this->payNotes ?: 'Pembayaran tagihan ' . $bill->name,
            ]);
        }

        $this->showPayModal = false;
        $this->payingId     = null;
        $this->dispatch('notify',
            message: 'Tagihan ' . $bill->name . ' ditandai lunas' . ($this->autoCreateTransaction ? ' & transaksi dicatat.' : '.'),
            type: 'success'
        );
    }

    // ── Quick toggle status ───────────────────────────────────────────────
    public function togglePause(int $id): void
    {
        $bill = Bill::findOrFail($id);
        abort_if($bill->user_id !== auth()->id(), 403);
        $newStatus = $bill->status === 'active' ? 'paused' : 'active';
        $bill->update(['status' => $newStatus]);
        $label = $newStatus === 'active' ? 'diaktifkan' : 'dijeda';
        $this->dispatch('notify', message: "Tagihan {$bill->name} berhasil {$label}.", type: 'success');
    }

    public function selectIcon(string $icon): void
    {
        $this->icon           = $icon;
        $this->showIconPicker = false;
    }

    public function closeForm(): void
    {
        $this->showForm      = false;
        $this->showIconPicker = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId      = null;
        $this->name           = '';
        $this->category       = '';
        $this->amount         = '';
        $this->due_day        = 1;
        $this->frequency      = 'monthly';
        $this->status         = 'active';
        $this->payment_method = '';
        $this->icon           = '📄';
        $this->notes          = '';
        $this->showIconPicker = false;
        $this->resetValidation();
    }

    // ── Bulk select & delete ─────────────────────────────────────────────
    public function updatedSelectAll(bool $value): void
    {
        $this->selectedIds = $value
            ? $this->bills->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function updatedSelectedIds(): void
    {
        $this->selectAll = count($this->selectedIds) === $this->bills->count() && $this->bills->count() > 0;
    }

    public function openBulkDeleteModal(): void
    {
        if (empty($this->selectedIds)) return;
        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete(): void
    {
        $ids = array_map('intval', $this->selectedIds);

        Bill::whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->delete();

        $count = count($ids);
        $this->selectedIds        = [];
        $this->selectAll          = false;
        $this->showBulkDeleteModal = false;

        $this->dispatch('notify', message: "{$count} tagihan berhasil dihapus.", type: 'success');
    }

    public function cancelBulkDelete(): void
    {
        $this->showBulkDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.bills.index')
            ->layout('layouts.app', ['title' => 'Tagihan']);
    }
}