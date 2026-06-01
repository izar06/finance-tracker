<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tujuan Keuangan</h2>
            <p class="text-sm text-slate-500">Tetapkan target tabungan dan pantau progres Anda menuju tujuan finansial.</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25">
            <span>+</span> Tambah Tujuan
        </button>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['active' => '🎯 Aktif', 'completed' => '✅ Selesai', 'cancelled' => '❌ Dibatalkan', '' => '📋 Semua'] as $val => $label)
            <button wire:click="$set('filterStatus', '{{ $val }}')" wire:key="filter-{{ $val ?: 'all' }}"
                    class="px-3 py-2 text-sm font-medium rounded-xl transition-colors whitespace-nowrap
                           {{ $filterStatus === $val ? 'bg-primary-500 text-white shadow-md shadow-primary-500/25' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Goals Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($goals as $goal)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-2xl">
                            {{ $goal->icon }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 leading-tight">{{ $goal->name }}</h4>
                            @if($goal->deadline)
                                <p class="text-xs text-slate-400 mt-0.5">
                                    📅 {{ $goal->deadline->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        @if($goal->status === 'active')
                            <button wire:click="openAddSaving({{ $goal->id }})"
                                    class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Tambah Tabungan">
                                💰
                            </button>
                        @endif
                        <button wire:click="editGoal({{ $goal->id }})"
                                class="p-1.5 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            ✏️
                        </button>
                        <button wire:click="confirmDelete({{ $goal->id }})"
                                class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                            🗑️
                        </button>
                    </div>
                </div>

                @if($goal->description)
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $goal->description }}</p>
                @endif

                {{-- Progress --}}
                <div class="mb-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-700">
                            Rp {{ number_format($goal->current_amount, 0, ',', '.') }}
                        </span>
                        <span class="text-sm font-bold
                                     {{ $goal->progress_percentage >= 100 ? 'text-emerald-600' : 'text-primary-600' }}">
                            {{ $goal->progress_percentage }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full transition-all duration-500
                                    {{ $goal->progress_percentage >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary-400 to-primary-600' }}"
                             style="width: {{ min(100, $goal->progress_percentage) }}%"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-50">
                    <div>
                        <p class="text-xs text-slate-400">Target</p>
                        <p class="text-sm font-bold text-slate-700">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Sisa</p>
                        <p class="text-sm font-bold text-slate-700">
                            Rp {{ number_format($goal->remaining_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    @if($goal->status === 'completed')
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl">✅ Selesai</span>
                    @elseif($goal->status === 'cancelled')
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">❌ Dibatalkan</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm py-20 text-center">
                <span class="text-5xl block mb-4">🎯</span>
                <p class="text-slate-500 font-medium text-lg mb-2">Belum ada tujuan keuangan</p>
                <p class="text-sm text-slate-400 mb-5">Mulai tetapkan tujuan untuk memotivasi tabungan Anda</p>
                <button wire:click="openForm"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                    + Buat Tujuan Pertama
                </button>
            </div>
        @endforelse
    </div>

    @if($goals->hasPages())
        <div class="mt-5">{{ $goals->links() }}</div>
    @endif

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $editingId ? 'Edit Tujuan' : 'Tujuan Keuangan Baru' }}
                    </h3>
                    <button wire:click="closeForm" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <form wire:submit="saveGoal" class="p-6 space-y-4">
                    {{-- Icon Picker --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ikon</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Models\FinancialGoal::$icons as $ic)
                                <button type="button" wire:click="$set('icon', '{{ $ic }}')"
                                        class="w-10 h-10 text-xl rounded-xl border-2 transition-all
                                               {{ $icon === $ic ? 'border-primary-500 bg-primary-50' : 'border-slate-200 hover:border-slate-300' }}">
                                    {{ $ic }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Tujuan</label>
                        <input wire:model="name" type="text" placeholder="Contoh: DP Rumah"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none @error('name') border-rose-400 @enderror">
                        @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Target (Rp)</label>
                            <input type="hidden" wire:model="target_amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none @error('target_amount') border-rose-400 @enderror">
                            @error('target_amount') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Dana Awal (Rp)</label>
                            <input type="hidden" wire:model="current_amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tenggat Waktu (opsional)</label>
                        <input wire:model="deadline" type="date"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none @error('deadline') border-rose-400 @enderror">
                        @error('deadline') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi (opsional)</label>
                        <textarea wire:model="description" rows="2" placeholder="Deskripsi tujuan..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none resize-none"></textarea>
                    </div>

                    @if($editingId)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select wire:model="status"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                                class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                            {{ $editingId ? 'Simpan Perubahan' : 'Buat Tujuan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Add Saving Modal --}}
    @if($showAddSavingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Setor Dana Tabungan</h3>
                    <button wire:click="$set('showAddSavingModal', false)" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah Tabungan (Rp)</label>
                    <input type="hidden" wire:model="savingAmount" data-currency-model>
                    <input type="text" data-currency inputmode="numeric" placeholder="Masukkan jumlah..." autocomplete="off"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none text-lg font-bold @error('savingAmount') border-rose-400 @enderror">
                    @error('savingAmount') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- #1-FIX: Toggle autoCreateTransaction agar transparan --}}
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 space-y-2 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-800">Catat sebagai transaksi</p>
                            <p class="text-xs text-emerald-600">Buat pengeluaran kategori "Tabungan" otomatis</p>
                        </div>
                        <button type="button" wire:click="$toggle('autoCreateSavingTransaction')"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0
                                       {{ $autoCreateSavingTransaction ? 'bg-emerald-500' : 'bg-slate-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform
                                         {{ $autoCreateSavingTransaction ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                    @if($autoCreateSavingTransaction)
                        <p class="text-xs text-emerald-600">
                            ✅ Setoran ini <strong>akan tercatat</strong> di halaman
                            <a href="{{ route('transactions') }}" class="underline font-semibold">Transaksi →</a>
                        </p>
                    @else
                        <p class="text-xs text-slate-500">ℹ️ Setoran hanya mengupdate progres goal, tidak dicatat di Transaksi.</p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showAddSavingModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="addSaving"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600">
                        💰 Setor Dana
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Tujuan?</h3>
                <p class="text-sm text-slate-500 mb-6">Data tujuan keuangan ini akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteGoal"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus</button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
    document.addEventListener('livewire:update', () => setTimeout(bindCurrencyInputs, 30));
    </script>
    @endpush
</div>
