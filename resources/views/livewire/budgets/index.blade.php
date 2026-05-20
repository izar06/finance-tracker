<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Anggaran</h2>
            <p class="text-sm text-slate-500">Tetapkan batas pengeluaran per kategori</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25 whitespace-nowrap self-start sm:self-auto">
            + Tambah Anggaran
        </button>
    </div>

    {{-- Month Navigator --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="flex items-center justify-between gap-4">
            <button wire:click="prevMonth"
                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors flex-shrink-0">
                ←
            </button>
            <div class="text-center">
                <p class="font-bold text-slate-800 text-base sm:text-lg">
                    {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                </p>
                @if($month == now()->month && $year == now()->year)
                    <span class="text-xs text-primary-500 font-medium">Bulan ini</span>
                @endif
            </div>
            <button wire:click="nextMonth"
                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors flex-shrink-0">
                →
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    @php $s = $this->summary; @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">
        {{-- Total Anggaran --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Total Anggaran</p>
            <p class="text-sm sm:text-xl font-bold text-slate-800 leading-tight">Rp {{ number_format($s['totalBudget'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $this->budgets->count() }} kategori</p>
        </div>
        {{-- Total Terpakai --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Terpakai</p>
            <p class="text-sm sm:text-xl font-bold text-slate-800 leading-tight">Rp {{ number_format($s['totalSpent'], 0, ',', '.') }}</p>
            @if($s['totalBudget'] > 0)
                <p class="text-xs text-slate-400 mt-0.5">{{ round(($s['totalSpent'] / $s['totalBudget']) * 100) }}% dari anggaran</p>
            @else
                <p class="text-xs text-slate-400 mt-0.5">—</p>
            @endif
        </div>
        {{-- Sisa Anggaran --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Sisa</p>
            @php $sisa = $s['totalBudget'] - $s['totalSpent']; @endphp
            <p class="text-sm sm:text-xl font-bold leading-tight {{ $sisa >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Rp {{ number_format(abs($sisa), 0, ',', '.') }}
            </p>
            <p class="text-xs mt-0.5 {{ $sisa >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                {{ $sisa >= 0 ? 'masih tersedia' : 'melebihi anggaran' }}
            </p>
        </div>
        {{-- Status --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1.5">Status</p>
            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    ✓ {{ $s['onTrack'] }} aman
                </span>
                @if($s['warning'] > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                        ⚠ {{ $s['warning'] }} hampir
                    </span>
                @endif
                @if($s['overBudget'] > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full">
                        ✕ {{ $s['overBudget'] }} lewat
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Budget List --}}
    @if($this->budgets->count() > 0)
        <div class="space-y-3 mb-5">
            @foreach($this->budgets as $budget)
                @php
                    $pct   = $budget->percentage;
                    $color = $budget->status_color;
                    $barColor = match($color) {
                        'rose'   => 'bg-rose-500',
                        'amber'  => 'bg-amber-400',
                        default  => 'bg-emerald-500',
                    };
                    $textColor = match($color) {
                        'rose'  => 'text-rose-600',
                        'amber' => 'text-amber-600',
                        default => 'text-emerald-600',
                    };
                    $bgLight = match($color) {
                        'rose'  => 'bg-rose-50 border-rose-100',
                        'amber' => 'bg-amber-50 border-amber-100',
                        default => 'bg-white border-slate-100',
                    };
                @endphp
                <div class="rounded-2xl border {{ $bgLight }} shadow-sm p-4 sm:p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-slate-800 text-sm sm:text-base">{{ $budget->category }}</h3>
                                @if($budget->is_over_budget)
                                    <span class="text-xs font-semibold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Melebihi!</span>
                                @elseif($pct >= 80)
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full">Hampir habis</span>
                                @endif
                            </div>
                            @if($budget->notes)
                                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $budget->notes }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="editBudget({{ $budget->id }})"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            <button wire:click="confirmDelete({{ $budget->id }})"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div class="mb-2.5">
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                                 style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>

                    {{-- Numbers --}}
                    <div class="flex items-center justify-between gap-2 text-xs sm:text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-slate-500">Terpakai: <span class="{{ $textColor }} font-semibold">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="flex items-center gap-3 text-right">
                            @if($budget->is_over_budget)
                                <span class="text-rose-500">Lebih Rp {{ number_format($budget->overspent, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-400">Sisa Rp {{ number_format($budget->remaining, 0, ',', '.') }}</span>
                            @endif
                            <span class="{{ $textColor }} font-bold">{{ $pct }}%</span>
                        </div>
                    </div>
                    <div class="mt-1 text-xs text-slate-400">
                        Anggaran: Rp {{ number_format($budget->amount, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm py-16 text-center mb-5">
            <span class="text-5xl block mb-4">💰</span>
            <p class="font-semibold text-slate-700 mb-1">Belum ada anggaran</p>
            <p class="text-sm text-slate-400 mb-5">Tetapkan batas pengeluaran untuk bulan ini</p>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                + Tambah Anggaran Pertama
            </button>
        </div>
    @endif

    {{-- Pengeluaran tanpa anggaran --}}
    @if($this->unbudgetedExpenses->count() > 0)
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 sm:p-5">
            <div class="flex items-start gap-3 mb-3">
                <span class="text-xl flex-shrink-0">⚠️</span>
                <div>
                    <p class="font-semibold text-amber-800 text-sm">Pengeluaran Tanpa Anggaran</p>
                    <p class="text-xs text-amber-600">Kategori berikut ada transaksi tapi belum dianggarkan bulan ini</p>
                </div>
            </div>
            <div class="space-y-2">
                @foreach($this->unbudgetedExpenses as $ue)
                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-2.5 border border-amber-100">
                        <div>
                            <span class="text-sm font-medium text-slate-700">{{ $ue->category }}</span>
                            <span class="text-xs text-slate-400 ml-2">{{ $ue->count }} transaksi</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-rose-600">Rp {{ number_format($ue->total, 0, ',', '.') }}</span>
                            <button wire:click="openFormWithCategory('{{ $ue->category }}')"
                                    class="text-xs text-primary-500 hover:text-primary-700 font-medium whitespace-nowrap">
                                + Anggarkan
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeForm()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeForm"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10
                        max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">
                        {{ $editingId ? 'Edit Anggaran' : 'Tambah Anggaran' }}
                    </h3>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 text-xl leading-none">✕</button>
                </div>

                <div class="space-y-4">
                    {{-- Periode info --}}
                    <div class="bg-primary-50 rounded-xl px-4 py-2.5 text-sm text-primary-700 font-medium">
                        📅 Periode: {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        @if($editingId)
                            <div class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-600">
                                {{ $category }}
                            </div>
                        @else
                            <select wire:model="category"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none bg-white @error('category') border-rose-400 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(\App\Models\Category::expense()->whereNotIn('name', \App\Models\Budget::where('month', $month)->where('year', $year)->when($editingId, fn($q) => $q->where('id', '!=', $editingId))->pluck('category')->toArray())->orderBy('name')->get() as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                                @if($editingId && $category)
                                    <option value="{{ $category }}" selected>{{ $category }}</option>
                                @endif
                            </select>
                        @endif
                        @error('category') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        @if(!$editingId && count($this->availableCategories) === 0)
                            <p class="text-xs text-amber-500 mt-1">Semua kategori sudah memiliki anggaran bulan ini.</p>
                        @endif
                    </div>

                    {{-- Jumlah Anggaran --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Batas Anggaran</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                            <input type="hidden" wire:model="amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('amount') border-rose-400 @enderror">
                        </div>
                        @error('amount') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea wire:model="notes" rows="2"
                                  placeholder="Contoh: termasuk makan siang kantor"
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="closeForm"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveBudget" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveBudget">{{ $editingId ? 'Simpan Perubahan' : 'Tambah Anggaran' }}</span>
                        <span wire:loading wire:target="saveBudget">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm z-10">
                <div class="text-center mb-5">
                    <span class="text-4xl block mb-3">🗑️</span>
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus Anggaran?</h3>
                    <p class="text-sm text-slate-500">Anggaran ini akan dihapus permanen. Transaksi tidak terpengaruh.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteBudget"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
