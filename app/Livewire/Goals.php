<?php

namespace App\Livewire;

use App\Models\FinancialGoal;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Goals extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public bool $showAddSavingModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?int $savingGoalId = null;

    public string $filterStatus = 'active';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    // Form fields
    public string $name = '';
    public string $target_amount = '';
    public string $current_amount = '0';
    public string $deadline = '';
    public string $description = '';
    public string $status = 'active';
    public string $icon = '🎯';

    // Savings deposit
    public string $savingAmount = '';

    protected function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'target_amount'  => ['required', function($a,$v,$f){ $n=str_replace(".","",$v); if(!is_numeric($n)||$n<1)$f("Target harus berupa angka lebih dari 0."); }],
            'current_amount' => ['required', function($a,$v,$f){ $n=str_replace(['.', ','], '', $v); if(!is_numeric($n)||(int)$n<0)$f("Dana saat ini harus berupa angka."); }],
            'deadline'       => 'nullable|date|after:today',
            'description'    => 'nullable|string|max:500',
            'status'         => 'required|in:active,completed,cancelled',
            'icon'           => 'required|string',
        ];
    }

    protected array $validationAttributes = [
        'name'           => 'Nama Tujuan',
        'target_amount'  => 'Target',
        'current_amount' => 'Dana Saat Ini',
        'deadline'       => 'Tenggat Waktu',
        'description'    => 'Deskripsi',
        'icon'           => 'Ikon',
    ];

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editGoal(int $id): void
    {
        
        $goal = FinancialGoal::findOrFail($id);
        abort_if($goal->user_id !== auth()->id(), 403);
        $this->editingId = $id;
        $this->name = $goal->name;
        $this->target_amount = (string) (int) $goal->target_amount;
        $this->current_amount = (string) (int) $goal->current_amount;
        $this->deadline = $goal->deadline?->format('Y-m-d') ?? '';
        $this->description = $goal->description ?? '';
        $this->status = $goal->status;
        $this->icon = $goal->icon;
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function saveGoal(): void
    {
        $this->validate();

        $data = [
            'name'           => $this->name,
            'target_amount'  => (float) str_replace(['.', ','], ['', '.'], $this->target_amount),
            'current_amount' => (float) str_replace(['.', ','], ['', '.'], $this->current_amount),
            'deadline'       => $this->deadline ?: null,
            'description'    => $this->description ?: null,
            'status'         => $this->status,
            'icon'           => $this->icon,
        ];

        if ($this->editingId) {
            FinancialGoal::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Tujuan keuangan berhasil diperbarui.', type: 'success');
        } else {
            FinancialGoal::create($data);
            $this->dispatch('notify', message: 'Tujuan keuangan berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function openAddSaving(int $id): void
    {
        $this->savingGoalId = $id;
        $this->savingAmount = '';
        $this->showAddSavingModal = true;
        $this->dispatch('currency:rebind');
    }

    public function addSaving(): void
    {
        $this->validate(['savingAmount' => ['required', function($a,$v,$f){ $n=str_replace(".","",$v); if(!is_numeric($n)||$n<1)$f("Jumlah minimal Rp 1."); }]], [
            'savingAmount.required' => 'Jumlah tabungan wajib diisi.',
            'savingAmount.numeric'  => 'Jumlah harus berupa angka.',
            'savingAmount.min'      => 'Jumlah minimal Rp 1.',
        ]);

        $goal = FinancialGoal::findOrFail($this->savingGoalId);
        abort_if($goal->user_id !== auth()->id(), 403);

        $amount    = (float) str_replace(['.', ','], ['', '.'], $this->savingAmount);
        $newAmount = $goal->current_amount + $amount;
        $goal->update(['current_amount' => $newAmount]);

        // Otomatis catat sebagai transaksi pengeluaran
        Transaction::create([
            'type'     => 'expense',
            'title'    => 'Tabungan: ' . $goal->name,
            'amount'   => $amount,
            'category' => 'Tabungan',
            'date'     => now()->format('Y-m-d'),
            'notes'    => 'Setoran tujuan keuangan: ' . $goal->name,
        ]);

        if ($newAmount >= $goal->target_amount) {
            $goal->update(['status' => 'completed']);
            $this->dispatch('notify', message: '🎉 Selamat! Tujuan keuangan tercapai!', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Tabungan berhasil ditambahkan & dicatat sebagai transaksi.', type: 'success');
        }

        $this->showAddSavingModal = false;
        $this->savingGoalId = null;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteGoal(): void
    {
        FinancialGoal::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('notify', message: 'Tujuan keuangan berhasil dihapus.', type: 'success');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->target_amount = '';
        $this->current_amount = '0';
        $this->deadline = '';
        $this->description = '';
        $this->status = 'active';
        $this->icon = '🎯';
        $this->resetValidation();
    }

    public function render()
    {
        $goals = FinancialGoal::when(
            $this->filterStatus,
            fn($q) => $q->where('status', $this->filterStatus)
        )->orderBy('deadline')->paginate(9);

        return view('livewire.goals.index', [
            'goals' => $goals,
        ])->layout('layouts.app', ['title' => 'Tujuan Keuangan']);
    }
}
