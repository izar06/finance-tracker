<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Transaksi</h2>
            <p class="text-sm text-slate-500">Kelola pemasukan dan pengeluaran Anda</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(count($selectedIds) > 0)
                <button wire:click="confirmBulkDelete"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-rose-500 border border-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                    <span>🗑️</span> Hapus ({{ count($selectedIds) }})
                </button>
            @endif
            <button wire:click="exportExcel"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📊</span> Excel
            </button>
            <button wire:click="exportPdf"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📄</span> PDF
            </button>
            <button wire:click="openImportModal"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📥</span> Import
            </button>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25">
                <span>+</span> Tambah Transaksi
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Row 1 --}}
            <div class="lg:col-span-2">
                <input wire:model.live.debounce.400ms="search" type="text"
                       placeholder="🔍 Cari transaksi..."
                       class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none">
            </div>
            <select wire:model.live="filterType"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Tipe</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>
            <select wire:model.live="filterCategory"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white {{ $filterCategory ? 'border-primary-400 ring-2 ring-primary-200' : '' }}">
                <option value="">Semua Kategori</option>
                @foreach($this->categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            {{-- Row 2 --}}
            <select wire:model.live="filterPaymentMethod"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Metode</option>
                @foreach(\App\Models\PaymentMethod::ordered() as $pm)
                    <option value="{{ $pm->name }}">{{ $pm->icon }} {{ $pm->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterMonth"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterYear"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Tahun</option>
                @foreach(range(now()->year, now()->year - 3) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterRecurring"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua</option>
                <option value="recurring">🔁 Berulang saja</option>
                <option value="not_recurring">Sekali saja</option>
            </select>
        </div>

        {{-- Active filter badge --}}
        @if($filterCategory)
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-slate-400">Filter aktif:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200 rounded-lg">
                    🏷️ {{ $filterCategory }}
                    <button wire:click="$set('filterCategory', '')" class="hover:text-primary-900 font-bold leading-none">✕</button>
                </span>
            </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($transactions as $tx)
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm flex-shrink-0
                                        {{ $tx->type === 'income' ? 'bg-emerald-50' : 'bg-rose-50' }}">
                                {{ $tx->is_recurring ? '🔁' : ($tx->type === 'income' ? '📥' : '📤') }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800 text-sm truncate">{{ $tx->title }}</p>
                                <p class="text-xs text-slate-400">{{ $tx->date->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-sm whitespace-nowrap flex-shrink-0 {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg">{{ $tx->category }}</span>
                            @if($tx->is_recurring)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-violet-50 text-violet-700 rounded-lg">
                                    🔁 {{ \App\Models\Transaction::$recurringFrequencies[$tx->recurring_frequency] ?? '' }}
                                </span>
                            @endif
                            @if($tx->recurring_parent_id)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg">⚡ Otomatis</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            @if($tx->is_recurring)
                                <button wire:click="showRecurringInfo({{ $tx->id }})" class="p-1.5 text-violet-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" title="Info Berulang">🔁</button>
                            @endif
                            <button wire:click="editTransaction({{ $tx->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            <button wire:click="confirmDelete({{ $tx->id }})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <span class="text-4xl block mb-3">💳</span>
                    <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
                    <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau tambah transaksi baru</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-center px-4 py-3.5 font-semibold text-slate-600 w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                   class="w-4 h-4 rounded accent-primary-500 cursor-pointer">
                        </th>
                        <th class="text-center px-3 py-3.5 font-semibold text-slate-600 w-12">No</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">
                            <button wire:click="sortColumn('date')" class="flex items-center gap-1 hover:text-primary-600">
                                Tanggal
                                @if($sortBy === 'date')<span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>@endif
                            </button>
                        </th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Judul</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Kategori</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Metode</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tipe</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-slate-600">
                            <button wire:click="sortColumn('amount')" class="flex items-center gap-1 ml-auto hover:text-primary-600">
                                Jumlah
                                @if($sortBy === 'amount')<span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>@endif
                            </button>
                        </th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50 transition-colors {{ $tx->is_recurring ? 'bg-violet-50/30' : '' }} {{ in_array((string)$tx->id, $selectedIds) ? 'bg-rose-50/40' : '' }}">
                            <td class="px-4 py-3.5 text-center">
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ $tx->id }}"
                                       class="w-4 h-4 rounded accent-primary-500 cursor-pointer">
                            </td>
                            <td class="px-3 py-3.5 text-center text-sm text-slate-400 font-medium">
                                {{ $transactions->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ $tx->date->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if($tx->is_recurring)
                                        <span class="text-violet-500 text-base" title="Template berulang">🔁</span>
                                    @elseif($tx->recurring_parent_id)
                                        <span class="text-blue-400 text-xs" title="Dibuat otomatis">⚡</span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $tx->title }}</p>
                                        @if($tx->notes)
                                            <p class="text-xs text-slate-400 truncate max-w-xs">{{ $tx->notes }}</p>
                                        @endif
                                        @if($tx->is_recurring && $tx->next_recurring_date)
                                            <p class="text-xs text-violet-500 mt-0.5">
                                                Berikutnya: {{ $tx->next_recurring_date->translatedFormat('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg">{{ $tx->category }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($tx->payment_method)
                                    @php $pmIcon = \App\Models\PaymentMethod::withoutGlobalScopes()->where('user_id', auth()->id())->where('name', $tx->payment_method)->value('icon') ?? '🔖'; @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg">
                                        {{ $pmIcon }} {{ $tx->payment_method }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if($tx->type === 'income')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg">📥 Pemasukan</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-50 rounded-lg">📤 Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($tx->is_recurring)
                                        <button wire:click="showRecurringInfo({{ $tx->id }})"
                                                class="p-1.5 text-violet-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors"
                                                title="Info transaksi berulang">🔁</button>
                                    @endif
                                    <button wire:click="editTransaction({{ $tx->id }})"
                                            class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">✏️</button>
                                    <button wire:click="confirmDelete({{ $tx->id }})"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <span class="text-4xl block mb-3">💳</span>
                                <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
                                <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau tambah transaksi baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $transactions->links() }}</div>
        @endif

        {{-- Summary Bar --}}
        @if($filteredSummary['count'] > 0)
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <p class="text-xs text-slate-400 font-medium">
                    Ringkasan dari <span class="font-semibold text-slate-600">{{ number_format($filteredSummary['count']) }}</span> transaksi
                    @if($search || $filterType || $filterCategory || $filterPaymentMethod || $filterMonth || $filterYear || $filterRecurring)
                        <span class="ml-1 text-primary-500">(hasil filter)</span>
                    @endif
                </p>
                <div class="flex flex-wrap items-center gap-3 sm:gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Total Pemasukan</p>
                            <p class="text-sm font-bold text-emerald-600">+ Rp {{ number_format($filteredSummary['income'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-400 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Total Pengeluaran</p>
                            <p class="text-sm font-bold text-rose-600">- Rp {{ number_format($filteredSummary['expense'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $filteredSummary['balance'] >= 0 ? 'bg-primary-400' : 'bg-amber-400' }} flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Saldo</p>
                            <p class="text-sm font-bold {{ $filteredSummary['balance'] >= 0 ? 'text-primary-600' : 'text-amber-600' }}">
                                {{ $filteredSummary['balance'] >= 0 ? '+' : '' }} Rp {{ number_format($filteredSummary['balance'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FORM MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}</h3>
                    <button wire:click="closeForm" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <form wire:submit="saveTransaction" class="p-6 space-y-4" id="transaction-form">

                    {{-- Type Toggle --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tipe Transaksi</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden">
                            <button type="button" wire:click="$set('type', 'income')"
                                    class="flex-1 py-2.5 text-sm font-semibold transition-colors {{ $type === 'income' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                📥 Pemasukan
                            </button>
                            <button type="button" wire:click="$set('type', 'expense')"
                                    class="flex-1 py-2.5 text-sm font-semibold transition-colors {{ $type === 'expense' ? 'bg-rose-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                📤 Pengeluaran
                            </button>
                        </div>
                        @error('type') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul</label>
                        <input wire:model="title" type="text" placeholder="Contoh: Gaji Bulanan"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('title') border-rose-400 @enderror">
                        @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah (Rp)</label>
                            <input type="hidden" wire:model="amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('amount') border-rose-400 @enderror">
                            @error('amount') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal</label>
                            <input wire:model="date" type="date"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('date') border-rose-400 @enderror">
                            @error('date') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        <select wire:model="category"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none bg-white @error('category') border-rose-400 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(\App\Models\Category::where('type', $type)->orderBy('name')->get() as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->icon }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Metode Pembayaran <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(\App\Models\PaymentMethod::ordered() as $pm)
                                <button type="button"
                                        wire:click="$set('payment_method', '{{ $payment_method === $pm->name ? '' : $pm->name }}')"
                                        class="flex flex-col items-center gap-1 px-2 py-2.5 text-xs font-medium rounded-xl border transition-all
                                               {{ $payment_method === $pm->name
                                                   ? 'border-primary-400 bg-primary-50 text-primary-700 shadow-sm'
                                                   : 'border-slate-200 text-slate-600 hover:border-primary-300 hover:bg-slate-50' }}">
                                    <span class="text-lg leading-none">{{ $pm->icon }}</span>
                                    <span class="leading-tight text-center">{{ $pm->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════ --}}
                    {{-- RECURRING SECTION --}}
                    {{-- ══════════════════════════════════════ --}}
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        {{-- Toggle header --}}
                        <button type="button" wire:click="$toggle('is_recurring')"
                                class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium transition-colors
                                       {{ $is_recurring ? 'bg-violet-50 text-violet-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                            <span class="flex items-center gap-2">
                                <span class="text-base">🔁</span>
                                Transaksi Berulang
                                @if($is_recurring)
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full">Aktif</span>
                                @endif
                            </span>
                            <span class="text-slate-400 transition-transform {{ $is_recurring ? 'rotate-180' : '' }}">▼</span>
                        </button>

                        @if($is_recurring)
                            <div class="p-4 space-y-3 border-t border-slate-100 bg-violet-50/30">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Frekuensi</label>
                                        <select wire:model.live="recurring_frequency"
                                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none bg-white">
                                            <option value="daily">📅 Setiap Hari</option>
                                            <option value="weekly">📅 Setiap Minggu</option>
                                            <option value="monthly">📅 Setiap Bulan</option>
                                            <option value="yearly">📅 Setiap Tahun</option>
                                        </select>
                                        @error('recurring_frequency') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1.5">
                                            Berakhir pada <span class="text-slate-400">(opsional)</span>
                                        </label>
                                        <input wire:model="recurring_ends_at" type="date"
                                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-300 outline-none @error('recurring_ends_at') border-rose-400 @enderror">
                                        @error('recurring_ends_at') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Preview jadwal --}}
                                @if($date && $recurring_frequency)
                                    @php
                                        $previewDate = \Carbon\Carbon::parse($date);
                                        $previews = [];
                                        for ($i = 0; $i < 3; $i++) {
                                            $previewDate = match($recurring_frequency) {
                                                'daily'   => $previewDate->copy()->addDay(),
                                                'weekly'  => $previewDate->copy()->addWeek(),
                                                'monthly' => $previewDate->copy()->addMonth(),
                                                'yearly'  => $previewDate->copy()->addYear(),
                                            };
                                            $previews[] = $previewDate->translatedFormat('d M Y');
                                        }
                                    @endphp
                                    <div class="bg-white rounded-xl p-3 border border-violet-100">
                                        <p class="text-xs font-medium text-slate-500 mb-2">📌 Jadwal berikutnya:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($previews as $preview)
                                                <span class="px-2.5 py-1 text-xs font-medium bg-violet-100 text-violet-700 rounded-lg">{{ $preview }}</span>
                                            @endforeach
                                            <span class="px-2.5 py-1 text-xs text-slate-400">dan seterusnya...</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    {{-- ══════════════════════════════════════ --}}

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan (opsional)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Tambahkan catatan..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none resize-none"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                                class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                            {{ $editingId ? 'Simpan Perubahan' : 'Tambah Transaksi' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- DELETE MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showDeleteModal)
        @php $deletingTx = $deletingId ? \App\Models\Transaction::find($deletingId) : null; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Transaksi?</h3>

                @if($deletingTx && ($deletingTx->is_recurring || $deletingTx->recurring_parent_id))
                    <p class="text-sm text-slate-500 mb-4">Ini adalah transaksi berulang. Pilih yang ingin dihapus:</p>
                    <div class="flex flex-col gap-2 mb-5 text-left">
                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ $deleteScope === 'single' ? 'border-rose-300 bg-rose-50' : '' }}">
                            <input type="radio" wire:model="deleteScope" value="single" class="accent-rose-500">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Hanya transaksi ini</p>
                                <p class="text-xs text-slate-400">Jadwal berikutnya tetap berjalan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ $deleteScope === 'all' ? 'border-rose-300 bg-rose-50' : '' }}">
                            <input type="radio" wire:model="deleteScope" value="all" class="accent-rose-500">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Hapus semua (template + riwayat)</p>
                                <p class="text-xs text-slate-400">Seluruh data berulang akan dihapus permanen</p>
                            </div>
                        </label>
                    </div>
                @else
                    <p class="text-sm text-slate-500 mb-6">Data transaksi ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
                @endif

                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteTransaction"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- RECURRING DETAIL MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showRecurringDetail && $recurringDetail)
        @php $rd = $recurringDetail; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        🔁 Info Transaksi Berulang
                    </h3>
                    <button wire:click="$set('showRecurringDetail', false)" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>
                <div class="p-5 space-y-3">
                    <div class="bg-violet-50 rounded-xl p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Judul</span>
                            <span class="font-medium text-slate-800">{{ $rd->title }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Frekuensi</span>
                            <span class="font-medium text-violet-700">{{ \App\Models\Transaction::$recurringFrequencies[$rd->recurring_frequency] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Mulai dari</span>
                            <span class="font-medium text-slate-800">{{ $rd->date->translatedFormat('d M Y') }}</span>
                        </div>
                        @if($rd->next_recurring_date)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Jadwal berikutnya</span>
                            <span class="font-medium text-violet-700">{{ $rd->next_recurring_date->translatedFormat('d M Y') }}</span>
                        </div>
                        @endif
                        @if($rd->recurring_ends_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Berakhir pada</span>
                            <span class="font-medium text-slate-800">{{ $rd->recurring_ends_at->translatedFormat('d M Y') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total dibuat</span>
                            <span class="font-medium text-slate-800">{{ $rd->recurringChildren()->withoutGlobalScopes()->count() }} transaksi</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button wire:click="editTransaction({{ $rd->id }})"
                                class="flex-1 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            ✏️ Edit Template
                        </button>
                        <button wire:click="stopRecurring({{ $rd->id }})"
                                class="flex-1 py-2.5 text-sm font-medium text-rose-700 bg-rose-50 rounded-xl hover:bg-rose-100 transition-colors"
                                onclick="return confirm('Hentikan transaksi berulang ini? Transaksi yang sudah ada tidak akan terhapus.')">
                            ⏹ Hentikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- BULK DELETE MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showBulkDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus {{ count($selectedIds) }} Transaksi?</h3>
                <p class="text-sm text-slate-500 mb-6">Semua transaksi yang dipilih akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showBulkDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="bulkDeleteTransactions"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus Semua</button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- IMPORT MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showImportModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        📥 Import Transaksi
                    </h3>
                    <button wire:click="closeImportModal" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <div class="p-5 space-y-4">

                    {{-- Result state (setelah import) --}}
                    @if($importedCount !== null)
                        <div class="space-y-3">
                            {{-- Success summary --}}
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <span class="text-xl">✅</span>
                                <div>
                                    <p class="text-sm font-semibold text-emerald-700">{{ $importedCount }} transaksi berhasil diimport</p>
                                    @if($skippedCount > 0)
                                        <p class="text-xs text-emerald-600">{{ $skippedCount }} baris dilewati</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Error list --}}
                            @if(count($importErrors) > 0)
                                <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                                    <p class="text-xs font-semibold text-rose-700 mb-2">Detail baris yang dilewati:</p>
                                    <ul class="space-y-1">
                                        @foreach($importErrors as $err)
                                            <li class="text-xs text-rose-600">• {{ $err }}</li>
                                        @endforeach
                                    </ul>
                                    @if($skippedCount > count($importErrors))
                                        <p class="text-xs text-rose-400 mt-1">...dan {{ $skippedCount - count($importErrors) }} baris lainnya.</p>
                                    @endif
                                </div>
                            @endif

                            <button wire:click="closeImportModal"
                                    class="w-full py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                                Selesai
                            </button>
                        </div>

                    {{-- Upload state --}}
                    @else
                        {{-- Format info --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-xs text-blue-700 space-y-1">
                            <p class="font-semibold">Format kolom yang didukung:</p>
                            <p><span class="font-medium">tipe</span> — income/expense atau pemasukan/pengeluaran</p>
                            <p><span class="font-medium">judul</span> — nama transaksi (wajib)</p>
                            <p><span class="font-medium">jumlah</span> — nominal angka (wajib)</p>
                            <p><span class="font-medium">kategori</span> — nama kategori</p>
                            <p><span class="font-medium">tanggal</span> — format YYYY-MM-DD</p>
                            <p><span class="font-medium">metode_pembayaran</span> — opsional</p>
                            <p><span class="font-medium">catatan</span> — opsional</p>
                        </div>

                        {{-- Template download --}}
                        <button wire:click="downloadTemplate"
                                class="w-full flex items-center justify-center gap-2 py-2 text-xs font-medium text-primary-600 border border-primary-200 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors">
                            ⬇️ Download template CSV
                        </button>

                        {{-- File input --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Pilih file (.xlsx, .xls, .csv)</label>
                            <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                                   class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 border border-slate-200 rounded-xl p-1 cursor-pointer">
                            @error('importFile') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Loading indicator saat upload --}}
                        <div wire:loading wire:target="importFile" class="text-xs text-slate-400 text-center">Mengunggah file...</div>

                        <div class="flex gap-3 pt-1">
                            <button wire:click="closeImportModal"
                                    class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                            <button wire:click="importTransactions"
                                    wire:loading.attr="disabled"
                                    wire:target="importTransactions"
                                    class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                                <span wire:loading.remove wire:target="importTransactions">Import Sekarang</span>
                                <span wire:loading wire:target="importTransactions">Memproses...</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>