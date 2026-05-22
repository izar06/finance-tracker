<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Transaction;
use Livewire\Component;

class Categories extends Component
{
    public string $activeTab = 'expense';

    // Form
    public bool $showForm        = false;
    public bool $showDeleteModal = false;
    public ?int $editingId       = null;
    public ?int $deletingId      = null;

    // Fields
    public string $name = '';
    public string $icon = '🏷️';
    public string $type = 'expense';

    // Icon picker
    public bool $showIconPicker = false;

    public array $iconOptions = [
        // Umum
        '🏷️','💰','💵','💳','🏦','📊','📈','📉',
        // Pemasukan
        '💼','💻','🏢','🎁','🎀','🤝','📝',
        // Makanan
        '🍽️','🍔','🍜','☕','🛒','🥗','🍕',
        // Transport
        '🚗','🚌','✈️','⛽','🚂','🛵','🚕',
        // Belanja
        '🛍️','👔','👗','👠','🎽','💄','🧴',
        // Rumah & Utilitas
        '🏠','⚡','💧','📱','🌐','🔧','🪴',
        // Kesehatan
        '🏥','💊','🏋️','🧘','🦷','👓','🩺',
        // Pendidikan
        '📚','✏️','🎓','📐','🔬','🖥️','📖',
        // Hiburan
        '🎮','🎬','🎵','🎨','📸','🎭','🎲',
        // Lainnya
        '🐷','🎯','⭐','🔑','🧾','📦','🗂️',
    ];

    protected function rules(): array
    {
        $uniqueRule = \Illuminate\Validation\Rule::unique('categories', 'name')
            ->where('user_id', auth()->id())
            ->where('type', $this->type)
            ->ignore($this->editingId);

        return [
            'name' => ['required', 'string', 'max:50', $uniqueRule],
            'icon' => 'required|string|max:10',
            'type' => 'required|in:income,expense',
        ];
    }

    protected array $validationAttributes = [
        'name' => 'Nama Kategori',
        'icon' => 'Ikon',
    ];

    public function getExpenseCategoriesProperty()
    {
        return Category::expense()->orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    public function getIncomeCategoriesProperty()
    {
        return Category::income()->orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function openForm(string $type = null): void
    {
        $this->resetForm();
        $this->type      = $type ?? $this->activeTab;
        $this->showForm  = true;
    }

    public function editCategory(int $id): void
    {
        $cat = Category::findOrFail($id);
        abort_if($cat->user_id !== auth()->id(), 403);
        $this->editingId = $id;
        $this->name      = $cat->name;
        $this->icon      = $cat->icon;
        $this->type      = $cat->type;
        $this->showForm  = true;
    }

    public function saveCategory(): void
    {
        $this->validate();

        $data = ['name' => trim($this->name), 'icon' => $this->icon, 'type' => $this->type];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Kategori berhasil diperbarui.', type: 'success');
        } else {
            Category::create($data);
            $this->dispatch('notify', message: 'Kategori berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId     = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        $cat = Category::findOrFail($this->deletingId);

        // Cek apakah kategori masih dipakai di transaksi
        $usedCount = Transaction::where('category', $cat->name)->count();
        if ($usedCount > 0) {
            $this->showDeleteModal = false;
            $this->dispatch('notify',
                message: "Kategori \"{$cat->name}\" tidak bisa dihapus karena masih dipakai di {$usedCount} transaksi.",
                type: 'error'
            );
            return;
        }

        $cat->delete();
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->dispatch('notify', message: 'Kategori berhasil dihapus.', type: 'success');
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
        $this->editingId = null;
        $this->name      = '';
        $this->icon      = '🏷️';
        $this->type      = $this->activeTab;
        $this->showIconPicker = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.categories.index')->layout('layouts.app', [
            'title' => 'Kategori',
        ]);
    }
}
