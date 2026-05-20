<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-2xl">🛡️</span>
                <h2 class="text-xl font-bold text-slate-800">Manajemen User</h2>
            </div>
            <p class="text-sm text-slate-500">Kelola semua akun pengguna aplikasi</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-full border border-amber-200">
                🔐 Mode Superadmin
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    @php $stats = $this->stats; @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Total User</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-400 mt-0.5">terdaftar</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Aktif</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['active'] }}</p>
            <p class="text-xs text-slate-400 mt-0.5">user aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Disuspend</p>
            <p class="text-2xl font-bold text-rose-600">{{ $stats['suspended'] }}</p>
            <p class="text-xs text-slate-400 mt-0.5">user suspend</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-sm bg-amber-50">
            <p class="text-xs text-amber-600 mb-1">Superadmin</p>
            <p class="text-2xl font-bold text-amber-700">{{ $stats['superadmin'] }}</p>
            <p class="text-xs text-amber-500 mt-0.5">akun admin</p>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                <input wire:model.live.debounce.300ms="search"
                       type="text" placeholder="Cari nama atau email..."
                       class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none">
            </div>
            <select wire:model.live="filterRole"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Role</option>
                <option value="user">User</option>
                <option value="superadmin">Superadmin</option>
            </select>
            <select wire:model.live="filterStatus"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="suspended">Disuspend</option>
            </select>
        </div>
    </div>

    {{-- User Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($this->users as $user)
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0
                                        {{ $user->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700' }}">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">Anda</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="showDetail({{ $user->id }})"
                                    class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">👁️</button>
                            <button wire:click="editUser({{ $user->id }})"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            @if($user->id !== auth()->id())
                                <button wire:click="confirmDelete({{ $user->id }})"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Role badge --}}
                        @if($user->role === 'superadmin')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">🛡️ Superadmin</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">👤 User</span>
                        @endif
                        {{-- Status badge --}}
                        @if($user->status === 'active')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">● Aktif</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-rose-100 text-rose-700 rounded-full">● Suspend</span>
                        @endif
                        <span class="text-xs text-slate-400">{{ $user->transactions_count }} transaksi</span>
                        <span class="text-xs text-slate-400">Bergabung {{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    {{-- Quick actions --}}
                    @if($user->id !== auth()->id())
                        <div class="flex gap-2 mt-3">
                            <button wire:click="toggleStatus({{ $user->id }})"
                                    class="flex-1 py-1.5 text-xs font-medium rounded-lg border transition-colors
                                           {{ $user->status === 'active' ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                {{ $user->status === 'active' ? '⏸ Suspend' : '▶ Aktifkan' }}
                            </button>
                            @if($user->role === 'user')
                                <button wire:click="promoteToSuperAdmin({{ $user->id }})"
                                        class="flex-1 py-1.5 text-xs font-medium rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition-colors">
                                    ⬆ Jadikan Admin
                                </button>
                            @else
                                <button wire:click="demoteToUser({{ $user->id }})"
                                        class="flex-1 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                                    ⬇ Jadikan User
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-16 text-center">
                    <span class="text-4xl block mb-3">👥</span>
                    <p class="text-slate-500 font-medium">Tidak ada user ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">User</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Role</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Transaksi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Login Terakhir</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Bergabung</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($this->users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            {{-- User info --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold flex-shrink-0
                                                {{ $user->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700' }}">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-3.5">
                                @if($user->role === 'superadmin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-lg">🛡️ Superadmin</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg">👤 User</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-lg">● Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-rose-100 text-rose-700 rounded-lg">● Suspended</span>
                                @endif
                            </td>

                            {{-- Transaksi --}}
                            <td class="px-5 py-3.5 text-slate-600">{{ number_format($user->transactions_count) }}</td>

                            {{-- Last login --}}
                            <td class="px-5 py-3.5 text-slate-500 text-xs">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '—' }}
                            </td>

                            {{-- Joined --}}
                            <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                                {{ $user->created_at->translatedFormat('d M Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="showDetail({{ $user->id }})"
                                            title="Detail"
                                            class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">👁️</button>
                                    <button wire:click="editUser({{ $user->id }})"
                                            title="Edit"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="toggleStatus({{ $user->id }})"
                                                title="{{ $user->status === 'active' ? 'Suspend' : 'Aktifkan' }}"
                                                class="p-1.5 rounded-lg transition-colors
                                                       {{ $user->status === 'active' ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }}">
                                            {{ $user->status === 'active' ? '⏸' : '▶' }}
                                        </button>
                                        @if($user->role === 'user')
                                            <button wire:click="promoteToSuperAdmin({{ $user->id }})"
                                                    title="Jadikan Superadmin"
                                                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">⬆</button>
                                        @else
                                            <button wire:click="demoteToUser({{ $user->id }})"
                                                    title="Jadikan User Biasa"
                                                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">⬇</button>
                                        @endif
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                                title="Hapus"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <span class="text-4xl block mb-3">👥</span>
                                <p class="text-slate-500 font-medium">Tidak ada user ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($this->users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $this->users->links() }}
            </div>
        @endif
    </div>

    {{-- ══ DETAIL MODAL ══ --}}
    @if($showDetailModal && $detailUser)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeModals()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">Detail User</h3>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
                </div>

                {{-- Avatar & basic info --}}
                <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold flex-shrink-0
                                {{ $detailUser->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700' }}">
                        {{ strtoupper(substr($detailUser->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-base">{{ $detailUser->name }}</p>
                        <p class="text-sm text-slate-500">{{ $detailUser->email }}</p>
                        <div class="flex gap-2 mt-1.5">
                            @if($detailUser->role === 'superadmin')
                                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">🛡️ Superadmin</span>
                            @else
                                <span class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">👤 User</span>
                            @endif
                            @if($detailUser->status === 'active')
                                <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">● Aktif</span>
                            @else
                                <span class="text-xs font-semibold bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full">● Suspended</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 mb-5">
                    @php
                        $txCount   = \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->count();
                        $txIncome  = (float) \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->where('type','income')->sum('amount');
                        $txExpense = (float) \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->where('type','expense')->sum('amount');
                        $assetVal  = (float) \App\Models\Asset::withoutGlobalScopes()->where('user_id', $detailUser->id)->sum('current_value');
                        $goalCount = \App\Models\FinancialGoal::withoutGlobalScopes()->where('user_id', $detailUser->id)->count();
                    @endphp
                    <div class="bg-emerald-50 rounded-xl p-3">
                        <p class="text-xs text-emerald-600 mb-0.5">Total Pemasukan</p>
                        <p class="font-bold text-emerald-700 text-sm">Rp {{ number_format($txIncome, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-3">
                        <p class="text-xs text-rose-600 mb-0.5">Total Pengeluaran</p>
                        <p class="font-bold text-rose-700 text-sm">Rp {{ number_format($txExpense, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-3">
                        <p class="text-xs text-blue-600 mb-0.5">Total Aset</p>
                        <p class="font-bold text-blue-700 text-sm">Rp {{ number_format($assetVal, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-500 mb-0.5">Transaksi / Tujuan</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $txCount }} / {{ $goalCount }}</p>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Bergabung</span>
                        <span class="font-medium text-slate-700">{{ $detailUser->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Login Terakhir</span>
                        <span class="font-medium text-slate-700">{{ $detailUser->last_login_at ? $detailUser->last_login_at->translatedFormat('d F Y, H:i') : '—' }}</span>
                    </div>
                </div>

                <button wire:click="closeModals"
                        class="w-full mt-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    @endif

    {{-- ══ EDIT MODAL ══ --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeModals()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">Edit User</h3>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama</label>
                        <input wire:model="editName" type="text"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('editName') border-rose-400 @enderror">
                        @error('editName') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input wire:model="editEmail" type="email"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('editEmail') border-rose-400 @enderror">
                        @error('editEmail') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                            <select wire:model="editRole"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white @error('editRole') border-rose-400 @enderror">
                                <option value="user">👤 User</option>
                                <option value="superadmin">🛡️ Superadmin</option>
                            </select>
                            @error('editRole') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select wire:model="editStatus"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white @error('editStatus') border-rose-400 @enderror">
                                <option value="active">● Aktif</option>
                                <option value="suspended">● Suspend</option>
                            </select>
                            @error('editStatus') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Password Baru
                            <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input wire:model="editPassword" type="password"
                               placeholder="Min. 8 karakter"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('editPassword') border-rose-400 @enderror">
                        @error('editPassword') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="closeModals"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveUser" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveUser">Simpan Perubahan</span>
                        <span wire:loading wire:target="saveUser">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ══ DELETE MODAL ══ --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm z-10">
                <div class="text-center mb-5">
                    <span class="text-4xl block mb-3">⚠️</span>
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus User?</h3>
                    <p class="text-sm text-slate-500">Semua data user (transaksi, aset, kategori) akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeModals"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteUser" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        <span wire:loading.remove wire:target="deleteUser">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteUser">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
