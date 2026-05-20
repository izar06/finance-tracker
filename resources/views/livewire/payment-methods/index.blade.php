<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Metode Pembayaran</h2>
            <p class="text-sm text-slate-500">Kelola metode pembayaran yang tersedia</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25 whitespace-nowrap self-start sm:self-auto">
            + Tambah Metode
        </button>
    </div>

    {{-- Grid list --}}
    @if($this->paymentMethods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-5">
            @foreach($this->paymentMethods as $pm)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-4">
                    {{-- Icon --}}
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                        {{ $pm->icon }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 text-sm truncate">{{ $pm->name }}</p>
                        @if($pm->is_default)
                            <span class="text-xs text-slate-400">Metode bawaan</span>
                        @else
                            <span class="text-xs text-primary-500">Metode kustom</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button wire:click="editPaymentMethod({{ $pm->id }})"
                                class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Edit">✏️</button>
                        <button wire:click="confirmDelete({{ $pm->id }})"
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                title="Hapus">🗑️</button>
                    </div>
                </div>
            @endforeach

            {{-- Add shortcut card --}}
            <button wire:click="openForm"
                    class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center justify-center gap-2 text-slate-400 hover:border-primary-300 hover:text-primary-500 hover:bg-primary-50 transition-colors min-h-[72px]">
                <span class="text-lg">+</span>
                <span class="text-sm font-medium">Tambah Metode</span>
            </button>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm py-16 text-center mb-5">
            <span class="text-5xl block mb-4">💳</span>
            <p class="font-semibold text-slate-700 mb-1">Belum ada metode pembayaran</p>
            <p class="text-sm text-slate-400 mb-5">Tambah metode untuk mulai mencatat transaksi</p>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                + Tambah Pertama
            </button>
        </div>
    @endif

    {{-- Info box --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
        <span class="text-lg flex-shrink-0 mt-0.5">ℹ️</span>
        <div>
            <p class="text-sm font-medium text-blue-700 mb-0.5">Tentang Metode Pembayaran</p>
            <p class="text-xs text-blue-600 leading-relaxed">Metode bawaan bisa diedit ikon dan namanya. Metode yang sudah dipakai di transaksi tidak bisa dihapus. Metode yang Anda tambah akan langsung tersedia saat mencatat transaksi.</p>
        </div>
    </div>

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeForm()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeForm"></div>
            <div class="relative bg-white w-full sm:max-w-sm sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">
                        {{ $editingId ? 'Edit Metode' : 'Tambah Metode Pembayaran' }}
                    </h3>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 text-xl leading-none">✕</button>
                </div>

                <div class="space-y-4">

                    {{-- Icon Picker --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Ikon</label>
                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="$toggle('showIconPicker')"
                                    class="w-12 h-12 rounded-xl border-2 border-slate-200 flex items-center justify-center text-2xl hover:border-primary-300 transition-colors flex-shrink-0 bg-blue-50">
                                {{ $icon }}
                            </button>
                            <p class="text-xs text-slate-400">Klik untuk pilih ikon</p>
                        </div>

                        @if($showIconPicker)
                            <div class="mt-3 p-3 border border-slate-200 rounded-xl bg-slate-50 max-h-44 overflow-y-auto">
                                <div class="grid grid-cols-8 gap-1">
                                    @foreach($iconOptions as $ico)
                                        <button type="button" wire:click="selectIcon('{{ $ico }}')"
                                                class="w-9 h-9 rounded-lg flex items-center justify-center text-xl hover:bg-white hover:shadow-sm transition-colors
                                                       {{ $icon === $ico ? 'bg-primary-100 ring-2 ring-primary-400' : '' }}">
                                            {{ $ico }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Metode</label>
                        <input wire:model="name" type="text"
                               placeholder="Contoh: GoPay, OVO, Paylater..."
                               maxlength="50"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none @error('name') border-rose-400 @enderror">
                        @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="closeForm"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="savePaymentMethod" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="savePaymentMethod">{{ $editingId ? 'Simpan' : 'Tambah' }}</span>
                        <span wire:loading wire:target="savePaymentMethod">Menyimpan...</span>
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
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus Metode?</h3>
                    <p class="text-sm text-slate-500">Metode yang masih dipakai di transaksi tidak dapat dihapus.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deletePaymentMethod" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        <span wire:loading.remove wire:target="deletePaymentMethod">Ya, Hapus</span>
                        <span wire:loading wire:target="deletePaymentMethod">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
