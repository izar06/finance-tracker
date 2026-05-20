<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use Livewire\Component;

class PaymentMethods extends Component
{
    public bool $showForm        = false;
    public bool $showDeleteModal = false;
    public ?int $editingId       = null;
    public ?int $deletingId      = null;

    public string $name = '';
    public string $icon = '💳';
    public bool   $showIconPicker = false;

    public array $iconOptions = [
        '💵','💳','💎','🏦','📱','📷','📝','🔖',
        '💰','🏧','💸','💹','🪙','💲','🤑','🏪',
        '📲','📳','🖥️','⌚','🛒','🏬','🏢','🏛️',
        '✈️','🚗','🛵','🚌','🚢','🏍️','🚁','🛺',
        '🎫','🎟️','🧾','📋','📄','📃','🗒️','📑',
    ];

    protected function rules(): array
    {
        $unique = \Illuminate\Validation\Rule::unique('payment_methods', 'name')
            ->where('user_id', auth()->id())
            ->ignore($this->editingId);

        return [
            'name' => ['required', 'string', 'max:50', $unique],
            'icon' => 'required|string|max:10',
        ];
    }

    protected array $validationAttributes = [
        'name' => 'Nama Metode',
        'icon' => 'Ikon',
    ];

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editPaymentMethod(int $id): void
    {
        $pm = PaymentMethod::findOrFail($id);
        $this->editingId = $id;
        $this->name      = $pm->name;
        $this->icon      = $pm->icon;
        $this->showForm  = true;
    }

    public function savePaymentMethod(): void
    {
        $this->validate();

        $data = ['name' => trim($this->name), 'icon' => $this->icon];

        if ($this->editingId) {
            PaymentMethod::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Metode pembayaran berhasil diperbarui.', type: 'success');
        } else {
            PaymentMethod::create($data);
            $this->dispatch('notify', message: 'Metode pembayaran berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function deletePaymentMethod(): void
    {
        $pm = PaymentMethod::findOrFail($this->deletingId);

        // Cek apakah masih dipakai di transaksi
        $usedCount = Transaction::where('payment_method', $pm->name)->count();
        if ($usedCount > 0) {
            $this->showDeleteModal = false;
            $this->dispatch('notify',
                message: "Metode \"{$pm->name}\" tidak bisa dihapus karena dipakai di {$usedCount} transaksi.",
                type: 'error'
            );
            return;
        }

        $pm->delete();
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->dispatch('notify', message: 'Metode pembayaran berhasil dihapus.', type: 'success');
    }

    public function selectIcon(string $icon): void
    {
        $this->icon           = $icon;
        $this->showIconPicker = false;
    }

    public function closeForm(): void
    {
        $this->showForm       = false;
        $this->showIconPicker = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId      = null;
        $this->name           = '';
        $this->icon           = '💳';
        $this->showIconPicker = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.payment-methods.index')
            ->layout('layouts.app', ['title' => 'Metode Pembayaran']);
    }
}
