<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdmin extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $filterRole   = '';
    public string $filterStatus = '';

    // Form — edit user
    public bool   $showEditModal   = false;
    public bool   $showDeleteModal = false;
    public bool   $showDetailModal = false;
    public ?int   $selectedUserId  = null;

    // Edit fields
    public string $editName   = '';
    public string $editEmail  = '';
    public string $editRole   = 'user';
    public string $editStatus = 'active';
    public string $editPassword = '';

    // Detail user
    public ?User $detailUser = null;

    protected function rules(): array
    {
        return [
            'editName'     => 'required|string|max:100',
            'editEmail'    => 'required|email|unique:users,email,' . $this->selectedUserId,
            'editRole'     => 'required|in:user,superadmin',
            'editStatus'   => 'required|in:active,suspended',
            'editPassword' => 'nullable|string|min:8',
        ];
    }

    protected array $validationAttributes = [
        'editName'     => 'Nama',
        'editEmail'    => 'Email',
        'editRole'     => 'Role',
        'editStatus'   => 'Status',
        'editPassword' => 'Password',
    ];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterRole(): void   { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    // ── Computed: user list ──────────────────────────────────────────────
    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterRole,   fn($q) => $q->where('role', $this->filterRole))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->withCount([
                'transactions' => fn($q) => $q->withoutGlobalScopes(),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    // ── Computed: stats ──────────────────────────────────────────────────
    public function getStatsProperty(): array
    {
        return [
            'total'      => User::count(),
            'active'     => User::where('status', 'active')->count(),
            'suspended'  => User::where('status', 'suspended')->count(),
            'superadmin' => User::where('role', 'superadmin')->count(),
        ];
    }

    // ── Detail Modal ─────────────────────────────────────────────────────
    public function showDetail(int $id): void
    {
        $this->detailUser      = User::findOrFail($id);
        $this->showDetailModal = true;
    }

    // ── Edit Modal ───────────────────────────────────────────────────────
    public function editUser(int $id): void
    {
        $user = User::findOrFail($id);

        // Jangan bisa edit superadmin lain jika bukan diri sendiri
        // (opsional — bisa diremove)
        $this->selectedUserId = $id;
        $this->editName       = $user->name;
        $this->editEmail      = $user->email;
        $this->editRole       = $user->role;
        $this->editStatus     = $user->status;
        $this->editPassword   = '';
        $this->showEditModal  = true;
    }

    public function saveUser(): void
    {
        $this->validate();

        $user = User::findOrFail($this->selectedUserId);

        // Jangan bisa demote diri sendiri
        if ($user->id === auth()->id() && $this->editRole !== 'superadmin') {
            $this->addError('editRole', 'Anda tidak bisa mengubah role diri sendiri.');
            return;
        }

        $data = [
            'name'   => $this->editName,
            'email'  => $this->editEmail,
            'role'   => $this->editRole,
            'status' => $this->editStatus,
        ];

        if ($this->editPassword) {
            $data['password'] = bcrypt($this->editPassword);
        }

        $user->update($data);

        $this->showEditModal = false;
        $this->resetEdit();
        $this->dispatch('notify', message: 'Data user berhasil diperbarui.', type: 'success');
    }

    // ── Quick Actions ─────────────────────────────────────────────────────
    public function toggleStatus(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', message: 'Tidak bisa mengubah status akun sendiri.', type: 'error');
            return;
        }

        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);

        $status = $user->fresh()->status === 'active' ? 'diaktifkan' : 'disuspend';
        $this->dispatch('notify', message: "User {$user->name} berhasil {$status}.", type: 'success');
    }

    public function promoteToSuperAdmin(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'superadmin']);
        $this->dispatch('notify', message: "{$user->name} dijadikan superadmin.", type: 'success');
    }

    public function demoteToUser(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', message: 'Tidak bisa menurunkan role diri sendiri.', type: 'error');
            return;
        }

        $user->update(['role' => 'user']);
        $this->dispatch('notify', message: "{$user->name} dikembalikan ke user biasa.", type: 'success');
    }

    // ── Delete ────────────────────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        if ($id === auth()->id()) {
            $this->dispatch('notify', message: 'Tidak bisa menghapus akun sendiri.', type: 'error');
            return;
        }

        $this->selectedUserId  = $id;
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        $user = User::findOrFail($this->selectedUserId);
        $name = $user->name;

        // Hapus semua data user (cascade via FK, tapi jaga-jaga)
        Transaction::withoutGlobalScopes()->where('user_id', $user->id)->delete();
        Asset::withoutGlobalScopes()->where('user_id', $user->id)->delete();
        Category::withoutGlobalScopes()->where('user_id', $user->id)->delete();

        $user->delete();

        $this->showDeleteModal = false;
        $this->selectedUserId  = null;
        $this->dispatch('notify', message: "User {$name} berhasil dihapus.", type: 'success');
    }

    public function closeModals(): void
    {
        $this->showEditModal   = false;
        $this->showDeleteModal = false;
        $this->showDetailModal = false;
        $this->detailUser      = null;
        $this->resetEdit();
    }

    private function resetEdit(): void
    {
        $this->selectedUserId = null;
        $this->editName       = '';
        $this->editEmail      = '';
        $this->editRole       = 'user';
        $this->editStatus     = 'active';
        $this->editPassword   = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.superadmin.index')
            ->layout('layouts.app', ['title' => 'Superadmin — Manajemen User']);
    }
}
