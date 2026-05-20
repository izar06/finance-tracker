<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Transaksi</h2>
            <p class="text-sm text-slate-500">Kelola pemasukan dan pengeluaran Anda</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="exportExcel"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📊</span> Excel
            </button>
            <button wire:click="exportPdf"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📄</span> PDF
            </button>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25">
                <span>+</span> Tambah Transaksi
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
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
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Mobile Card View (hidden on md+) --}}
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($transactions as $tx)
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm flex-shrink-0
                                        {{ $tx->type === 'income' ? 'bg-emerald-50' : 'bg-rose-50' }}">
                                {{ $tx->type === 'income' ? '📥' : '📤' }}
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
                            @if($tx->payment_method)
                                @php $pmIcon = \App\Models\PaymentMethod::withoutGlobalScopes()->where('user_id', auth()->id())->where('name', $tx->payment_method)->value('icon') ?? '🔖'; @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg">
                                    {{ $pmIcon }} {{ $tx->payment_method }}
                                </span>
                            @endif
                            @if($tx->notes)
                                <p class="text-xs text-slate-400 truncate max-w-[160px]">{{ $tx->notes }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
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

        {{-- Desktop Table View (hidden on mobile) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
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
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ $tx->date->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-800">{{ $tx->title }}</p>
                                @if($tx->notes)
                                    <p class="text-xs text-slate-400 truncate max-w-xs">{{ $tx->notes }}</p>
                                @endif
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
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="editTransaction({{ $tx->id }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">✏️</button>
                                    <button wire:click="confirmDelete({{ $tx->id }})" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
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
    </div>

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-screen overflow-y-auto">
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
                            {{-- data-currency: diformat JS, wire:model.blur supaya sync setelah format --}}
                            <input type="hidden" wire:model="amount" data-currency-model>
                            <input type="text"
                                   data-currency
                                   inputmode="numeric"
                                   placeholder="0"
                                   autocomplete="off"
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
                            Metode Pembayaran
                            <span class="text-slate-400 font-normal">(opsional)</span>
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
                        @error('payment_method') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

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

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Transaksi?</h3>
                <p class="text-sm text-slate-500 mb-6">Data transaksi ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteTransaction"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Currency re-binding ditangani oleh global script di layouts/app.blade.php --}}
</div>
